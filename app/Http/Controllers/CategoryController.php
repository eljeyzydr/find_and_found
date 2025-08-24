<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['items' => function($query) {
                $query->active();
            }])
            ->active()
            ->orderBy('name')
            ->get();

        // Tambahkan stats
        $stats = [
            'total_lost' => \App\Models\Item::lost()->active()->count(),
            'total_found' => \App\Models\Item::found()->active()->count(), 
            'total_resolved' => \App\Models\Item::resolved()->count(),
        ];

        $recent_items = \App\Models\Item::with(['category', 'location'])
            ->active()
            ->latest()
            ->take(8)
            ->get();

        return view('categories.index', compact('categories', 'stats', 'recent_items'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        $query = $category->items()
            ->with(['user', 'location'])
            ->active();

        // Filter by status if provided
        if (request('status') && in_array(request('status'), ['lost', 'found'])) {
            $query->where('status', request('status'));
        }

        // Sorting
        switch (request('sort')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $items = $query->paginate(12);

        // Get related categories (optional)
        $related_categories = Category::where('id', '!=', $category->id)
            ->active()
            ->withCount(['items' => function($query) {
                $query->active();
            }])
            ->orderBy('items_count', 'desc')
            ->take(4)
            ->get();

        return view('categories.show', compact('category', 'items', 'related_categories'));
    }

    // Admin methods
    public function adminIndex()
    {
        $categories = Category::withCount('items')
            ->latest()
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
            'icon.image' => 'Icon harus berupa gambar.',
            'icon.mimes' => 'Format icon harus jpeg, png, jpg, gif, atau svg.',
            'icon.max' => 'Ukuran icon maksimal 1MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $icon = $request->file('icon');
            $iconName = time() . '_' . Str::slug($request->name) . '.' . $icon->getClientOriginalExtension();
            
            // Store in public/uploads/icons directory
            $iconPath = $icon->storeAs('uploads/icons', $iconName, 'public');
            $data['icon'] = $iconName;
        }

        $category = Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama kategori harus diisi.',
            'name.unique' => 'Nama kategori sudah ada.',
            'icon.image' => 'Icon harus berupa gambar.',
            'icon.mimes' => 'Format icon harus jpeg, png, jpg, gif, atau svg.',
            'icon.max' => 'Ukuran icon maksimal 1MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($category->icon && Storage::disk('public')->exists('uploads/icons/' . $category->icon)) {
                Storage::disk('public')->delete('uploads/icons/' . $category->icon);
            }

            $icon = $request->file('icon');
            $iconName = time() . '_' . Str::slug($request->name) . '.' . $icon->getClientOriginalExtension();
            
            // Store new icon
            $iconPath = $icon->storeAs('uploads/icons', $iconName, 'public');
            $data['icon'] = $iconName;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category has items
        if ($category->items()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih ada item yang menggunakannya.');
        }

        // Delete icon file if exists
        if ($category->icon && Storage::disk('public')->exists('uploads/icons/' . $category->icon)) {
            Storage::disk('public')->delete('uploads/icons/' . $category->icon);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kategori berhasil {$status}!");
    }
}