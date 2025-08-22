<?php
// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $stats = [
            'my_items' => $user->items()->count(),
            'lost_items' => $user->items()->lost()->count(),
            'found_items' => $user->items()->found()->count(),
            'resolved_items' => $user->items()->resolved()->count(),
            'unread_notifications' => $user->getUnreadNotificationsCount(),
            'unread_chats' => $user->getUnreadChatsCount(),
        ];

        $recent_items = $user->items()->with(['category', 'location'])
            ->latest()
            ->take(5)
            ->get();

        $recent_notifications = $user->notifications()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_items', 'recent_notifications'));
    }

    public function profile()
    {
        return view('profile.show');
    }

    public function editProfile()
    {
        return view('profile.edit');
    }

   public function updateProfile(Request $request)
{
    $user = Auth::user();
    
    // Log untuk debugging
    \Log::info('Avatar upload attempt', [
        'user_id' => $user->id,
        'is_ajax' => $request->ajax(),
        'has_file' => $request->hasFile('avatar'),
        'method' => $request->method(),
        'content_type' => $request->header('Content-Type')
    ]);

    // Handle AJAX avatar upload khusus
    if ($request->hasFile('avatar') && $request->ajax()) {
        return $this->updateAvatar($request);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ], [
        'name.required' => 'Nama harus diisi.',
        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah digunakan.',
        'avatar.image' => 'File harus berupa gambar.',
        'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
        'avatar.max' => 'Ukuran gambar maksimal 5MB.',
    ]);

        if ($validator->fails()) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        return back()->withErrors($validator)->withInput();
    }

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
    ];

        // Handle avatar upload
        if ($request->hasFile('avatar') && !$request->ajax()) {
        $avatarName = $this->handleAvatarUpload($request->file('avatar'), $user);
        if ($avatarName) {
            $data['avatar'] = $avatarName;
        }
    }

    $user->update($data);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'avatar_url' => $user->avatar_url
        ]);
    }

    return redirect()->route('profile.show')
        ->with('success', 'Profil berhasil diperbarui.');
}

    // Handle AJAX avatar upload
    private function updateAvatar(Request $request)
{
    try {
        \Log::info('Processing avatar upload', [
            'file_size' => $request->file('avatar')->getSize(),
            'file_type' => $request->file('avatar')->getMimeType(),
            'original_name' => $request->file('avatar')->getClientOriginalName()
        ]);

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'avatar.required' => 'Avatar file is required.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'avatar.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            \Log::error('Avatar validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
$user = Auth::user();
        $avatarName = $this->handleAvatarUpload($request->file('avatar'), $user);
        
        if ($avatarName) {
            $user->update(['avatar' => $avatarName]);
            
            \Log::info('Avatar uploaded successfully', [
                'user_id' => $user->id,
                'avatar_name' => $avatarName
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Avatar updated successfully',
                'avatar_url' => $user->fresh()->avatar_url
            ]);
        }


        \Log::error('Avatar upload failed - handleAvatarUpload returned false');
        return response()->json([
            'success' => false,
            'message' => 'Failed to process avatar file'
        ], 500);

    } catch (\Exception $e) {
        \Log::error('Avatar upload exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Handle avatar file upload dengan logging
 */
private function handleAvatarUpload($file, $user)
{
    try {
        // Cek direktori avatars exists
        if (!Storage::disk('public')->exists('avatars')) {
            Storage::disk('public')->makeDirectory('avatars');
            \Log::info('Created avatars directory');
        }

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
            \Log::info('Deleted old avatar', ['old_avatar' => $user->avatar]);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $avatarName = 'avatar_' . $user->id . '_' . time() . '.' . $extension;

        \Log::info('Attempting to store avatar', [
            'filename' => $avatarName,
            'size' => $file->getSize(),
            'mime' => $file->getMimeType()
        ]);

        // Store the file
        $path = $file->storeAs('avatars', $avatarName, 'public');

        if ($path) {
            \Log::info('Avatar stored successfully', ['path' => $path]);
            return $avatarName;
        }

        \Log::error('Failed to store avatar file');
        return false;

    } catch (\Exception $e) {
        \Log::error('Avatar upload exception in handleAvatarUpload', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}
    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah.');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah.');
    }

    public function notifications()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->action_url);
    }

    public function markAllNotificationsAsRead()
    {
        Notification::markAllAsReadForUser(Auth::id());

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}