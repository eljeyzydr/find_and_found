<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home & Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/browse', [HomeController::class, 'browse'])->name('browse');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/map', [HomeController::class, 'map'])->name('map');

// Categories (public)
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Location API (public)
Route::get('/api/locations/search-nearby', [LocationController::class, 'searchNearby'])->name('api.locations.nearby');
Route::get('/api/locations/cities', [LocationController::class, 'getCities'])->name('api.locations.cities');
Route::get('/api/locations/provinces', [LocationController::class, 'getProvinces'])->name('api.locations.provinces');
Route::get('/api/locations/city/{city}', [LocationController::class, 'getLocationsByCity'])->name('api.locations.by-city');
Route::get('/api/locations/reverse-geocode', [LocationController::class, 'reverseGeocode'])->name('api.locations.reverse-geocode');
Route::get('/api/locations/geocode', [LocationController::class, 'geocode'])->name('api.locations.geocode');
Route::get('/api/map/data', [LocationController::class, 'mapData'])->name('api.map.data');

/*
|--------------------------------------------------------------------------
| Guest Routes (Only for non-authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.show');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.update-password');

    // Notifications
    Route::get('/notifications', [UserController::class, 'notifications'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [UserController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [UserController::class, 'markAllNotificationsAsRead'])->name('notifications.mark-all-read');

    // Items Management (PERHATIKAN URUTANNYA)
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.my-items');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{id}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::post('/items/{id}/mark-resolved', [ItemController::class, 'markAsResolved'])->name('items.mark-resolved');
    Route::post('/items/{id}/toggle-active', [ItemController::class, 'toggleActive'])->name('items.toggle-active');

    // Comments
    Route::post('/items/{itemId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Chat System
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{userId}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats', [ChatController::class, 'store'])->name('chats.store');
    Route::post('/chats/start', [ChatController::class, 'startChat'])->name('chats.start');
    Route::delete('/chats/{userId}', [ChatController::class, 'deleteConversation'])->name('chats.delete');

    // Chat API endpoints
    Route::get('/api/chats/{userId}/messages', [ChatController::class, 'getMessages'])->name('api.chats.messages');
    Route::get('/api/chats/{userId}/messages/new', [ChatController::class, 'getNewMessages'])->name('api.chats.new-messages');
    Route::post('/api/chats/{userId}/mark-read', [ChatController::class, 'markAsRead'])->name('api.chats.mark-read');
    Route::get('/api/chats/unread-count', [ChatController::class, 'getUnreadCount'])->name('api.chats.unread-count');

    // Notifications API
    Route::get('/api/notifications/count', function() {
        return response()->json([
            'count' => auth()->user()->getUnreadNotificationsCount()
        ]);
    })->name('api.notifications.count');

    // Reports
    Route::get('/items/{itemId}/report', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/items/{itemId}/report', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/my-reports', [ReportController::class, 'myReports'])->name('reports.my-reports');
});

// Public Items (harus setelah semua "/items/...") 
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// Test route
Route::get('/test-cek', function () {
    return 'Route Jalan';
});
Route::get('/debug-storage', function() {
    return response()->json([
        'storage_public_path' => storage_path('app/public'),
        'public_storage_path' => public_path('storage'),
        'symlink_exists' => is_link(public_path('storage')),
        'symlink_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
        'items_folder_exists' => is_dir(storage_path('app/public/items')),
        'avatars_folder_exists' => is_dir(storage_path('app/public/avatars')),
        'files_in_items' => Storage::disk('public')->files('items'),
        'test_item' => \App\Models\Item::whereNotNull('photos')->first(),
    ]);
});
Route::post('/profile/avatar/upload', [UserController::class, 'uploadAvatar'])->name('profile.avatar.upload');
Route::post('/profile/avatar/remove', [UserController::class, 'removeAvatar'])->name('profile.avatar.remove');
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');

    // Item Management
    Route::get('/items', [AdminController::class, 'items'])->name('items.index');
    Route::get('/items/{id}', [AdminController::class, 'showItem'])->name('items.show');
    Route::post('/items/{id}/toggle-status', [AdminController::class, 'toggleItemStatus'])->name('items.toggle-status');
    Route::delete('/items/{id}', [AdminController::class, 'deleteItem'])->name('items.destroy');

    // Category Management
    Route::get('/categories', [CategoryController::class, 'adminIndex'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');

    // Comment Management
    Route::get('/comments', [CommentController::class, 'adminIndex'])->name('comments.index');
    Route::post('/comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::post('/comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::post('/comments/bulk-action', [CommentController::class, 'bulkAction'])->name('comments.bulk-action');

    // Chat Management
    Route::get('/chats', [ChatController::class, 'adminIndex'])->name('chats.index');
    Route::delete('/chats/{id}', [ChatController::class, 'adminDestroy'])->name('chats.destroy');
    Route::post('/chats/bulk-delete', [ChatController::class, 'bulkDelete'])->name('chats.bulk-delete');

    // Report Management
    Route::get('/reports', [ReportController::class, 'adminIndex'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'adminShow'])->name('reports.show');
    Route::post('/reports/{id}/review', [ReportController::class, 'review'])->name('reports.review');
    Route::post('/reports/{id}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');
    Route::post('/reports/{id}/reject', [ReportController::class, 'reject'])->name('reports.reject');
    Route::post('/reports/bulk-action', [ReportController::class, 'bulkAction'])->name('reports.bulk-action');
});
