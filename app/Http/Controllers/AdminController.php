<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Chat;
use App\Models\Report;
use App\Models\Notification;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
            'total_items' => Item::count(),
            'active_items' => Item::active()->count(),
            'lost_items' => Item::lost()->count(),
            'found_items' => Item::found()->count(),
            'resolved_items' => Item::resolved()->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::active()->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::pending()->count(),
            'total_chats' => Chat::count(),
            'total_reports' => Report::count(),
            'pending_reports' => Report::pending()->count(),
            'total_notifications' => Notification::count(),
            'total_locations' => Location::count(),
        ];

        // Recent activities
        $recent_users = User::latest()->take(5)->get();
        $recent_items = Item::with(['user', 'category'])->latest()->take(5)->get();
        $recent_reports = Report::with(['item', 'reporter'])->latest()->take(5)->get();

        // Charts data
        $user_registrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $items_by_status = Item::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        $items_by_category = Category::withCount('items')
            ->orderBy('items_count', 'desc')
            ->take(10)
            ->get();

        $items_by_city = Location::select('city')
            ->selectRaw('COUNT(items.id) as items_count')
            ->join('items', 'locations.id', '=', 'items.location_id')
            ->groupBy('city')
            ->orderBy('items_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_users',
            'recent_items',
            'recent_reports',
            'user_registrations',
            'items_by_status',
            'items_by_category',
            'items_by_city'
        ));
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->role && in_array($request->role, ['user', 'admin'])) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->status && in_array($request->status, ['active', 'blocked'])) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->withCount(['items', 'comments', 'reports'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function showUser($id)
    {
        $user = User::with(['items.category', 'comments.item', 'reports.item'])
            ->withCount(['items', 'comments', 'reports', 'sentChats', 'receivedChats'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,blocked',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User berhasil diperbarui!');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat memblokir akun sendiri.');
        }

        $user->update(['status' => $user->status === 'active' ? 'blocked' : 'active']);

        $status = $user->status === 'active' ? 'diaktifkan' : 'diblokir';
        return back()->with('success', "User berhasil {$status}!");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    // Item Management
    public function items(Request $request)
    {
        $query = Item::with(['user', 'category', 'location']);

        // Filter by status
        if ($request->status && in_array($request->status, ['lost', 'found'])) {
            $query->where('status', $request->status);
        }

        // Filter by active status
        if ($request->active !== null) {
            $query->where('is_active', $request->active);
        }

        // Filter by resolved status
        if ($request->resolved !== null) {
            $query->where('is_resolved', $request->resolved);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function($subQ) use ($request) {
                      $subQ->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $items = $query->latest()->paginate(20);
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'categories'));
    }

    public function showItem($id)
    {
        $item = Item::with(['user', 'category', 'location', 'comments.user', 'reports.reporter'])
            ->findOrFail($id);

        return view('admin.items.show', compact('item'));
    }

    public function toggleItemStatus($id)
    {
        $item = Item::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Item berhasil {$status}!");
    }

    public function deleteItem($id)
    {
        $item = Item::findOrFail($id);
        
        // Delete photos
        if ($item->photos) {
            foreach ($item->photos as $photo) {
                \Storage::delete('public/items/' . $photo);
            }
        }

        $item->delete();

        return back()->with('success', 'Item berhasil dihapus!');
    }

    // Statistics & Reports
    public function statistics()
    {
        $data = [
            'users_by_month' => User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
            
            'items_by_month' => Item::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
                
            'resolved_items_by_month' => Item::selectRaw('YEAR(resolved_at) as year, MONTH(resolved_at) as month, COUNT(*) as count')
                ->whereNotNull('resolved_at')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
                
            'top_cities' => Location::select('city')
                ->selectRaw('COUNT(items.id) as items_count')
                ->join('items', 'locations.id', '=', 'items.location_id')
                ->groupBy('city')
                ->orderBy('items_count', 'desc')
                ->take(20)
                ->get(),
                
            'success_rate' => [
                'total_items' => Item::count(),
                'resolved_items' => Item::resolved()->count(),
                'success_percentage' => Item::count() > 0 ? 
                    round((Item::resolved()->count() / Item::count()) * 100, 2) : 0,
            ],
        ];

        return view('admin.statistics', compact('data'));
    }

    public function logs()
    {
        // TODO: Implement activity logs
        return view('admin.logs');
    }
}