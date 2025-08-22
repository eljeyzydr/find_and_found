<?php
// app/Http/Controllers/ItemController.php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    
    public function index(Request $request)
{
    $query = Item::with(['user', 'category', 'location'])
        ->active()
        ->latest();

    // Filter berdasarkan status
    if ($request->status && in_array($request->status, ['lost', 'found'])) {
        $query->where('status', $request->status);
    }

    // Filter berdasarkan kategori
    if ($request->category) {
        $query->where('category_id', $request->category);
    }

    // Filter berdasarkan lokasi/kota
    if ($request->city) {
        $query->whereHas('location', function($q) use ($request) {
            $q->where('city', 'like', "%{$request->city}%");
        });
    }

    // Search
    if ($request->search) {
        $query->search($request->search);
    }

    // Filter berdasarkan radius (jika ada koordinat)
    if ($request->latitude && $request->longitude && $request->radius) {
        $query->whereHas('location', function($q) use ($request) {
            $q->withinRadius($request->latitude, $request->longitude, $request->radius);
        });
    }

    // Sorting
    switch ($request->sort) {
        case 'popular':
            $query->popular();
            break;
        case 'oldest':
            $query->oldest();
            break;
        default:
            $query->latest();
    }

    $items = $query->paginate(12);

    // ✅ ambil kategori + jumlah item per kategori
    $categories = Category::active()
        ->withCount(['items' => function ($q) {
            $q->active(); // hanya hitung item aktif
        }])
        ->get();

    // ✅ total semua item aktif
    $totalItems = Item::active()->count();

    // ⬅️ ini yang hilang di kode kamu
    return view('items.index', compact('items', 'categories', 'totalItems'));
}



    public function show($id)
    {
        $item = Item::with(['user', 'category', 'location', 'comments.user'])
            ->findOrFail($id);

        // Increment views jika bukan pemilik
        if (!Auth::check() || Auth::id() !== $item->user_id) {
            $item->incrementViews();
        }

        $related_items = Item::with(['category', 'location'])
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->active()
            ->take(4)
            ->get();

        return view('items.show', compact('item', 'related_items'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:lost,found',
            'event_date' => 'required|date|before_or_equal:today',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            'address' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'city' => 'required|string|max:100',
            'province' => 'nullable|string|max:100',
        ], [
            'title.required' => 'Judul harus diisi.',
            'description.required' => 'Deskripsi harus diisi.',
            'category_id.required' => 'Kategori harus dipilih.',
            'status.required' => 'Status harus dipilih.',
            'event_date.required' => 'Tanggal kejadian harus diisi.',
            'event_date.before_or_equal' => 'Tanggal kejadian tidak boleh lebih dari hari ini.',
            'photos.max' => 'Maksimal 5 foto.',
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.max' => 'Ukuran foto maksimal 20MB.',
            'address.required' => 'Alamat harus diisi.',
            'latitude.required' => 'Koordinat GPS harus diisi.',
            'longitude.required' => 'Koordinat GPS harus diisi.',
            'city.required' => 'Kota harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create location first
        $location = Location::create([
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'province' => $request->province,
        ]);

        // Handle photo uploads
        $photoNames = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                // Generate unique filename
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                
                // Store ke public disk dalam folder items
                $photo->storeAs('items', $photoName, 'public');
                
                $photoNames[] = $photoName;
            }
        }

        // Create item
        $item = Item::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'location_id' => $location->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'event_date' => $request->event_date,
            'photos' => $photoNames,
        ]);

        return redirect()->route('items.show', $item->id)
            ->with('success', 'Item berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $item = Item::with(['location'])->findOrFail($id);

        if (!$item->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat mengedit item ini.');
        }

        $categories = Category::active()->get();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::with(['location'])->findOrFail($id);

        if (!$item->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat mengedit item ini.');
        }

        // store()
$validator = Validator::make($request->all(), [
    'title' => 'required|string|max:255',
    'description' => 'required|string',
    'category_id' => 'required|exists:categories,id',
    'status' => 'required|in:lost,found',
    'event_date' => 'required|date|before_or_equal:today',
    'photos' => 'nullable|array|max:5',
    'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:20480', // 20MB
    'address' => 'required|string',
    'latitude' => 'required|numeric|between:-90,90',
    'longitude' => 'required|numeric|between:-180,180',
    'city' => 'required|string|max:100',
    'province' => 'nullable|string|max:100',
], [
    'title.required' => 'Judul harus diisi.',
    'description.required' => 'Deskripsi harus diisi.',
    'category_id.required' => 'Kategori harus dipilih.',
    'status.required' => 'Status harus dipilih.',
    'event_date.required' => 'Tanggal kejadian harus diisi.',
    'event_date.before_or_equal' => 'Tanggal kejadian tidak boleh lebih dari hari ini.',
    'photos.max' => 'Maksimal 5 foto.',
    'photos.*.image' => 'File harus berupa gambar.',
    'photos.*.mimes' => 'Format foto harus jpeg, png, jpg, atau gif.',
    'photos.*.max' => 'Ukuran foto maksimal :max kilobytes.', // otomatis sesuai rule
    'address.required' => 'Alamat harus diisi.',
    'latitude.required' => 'Koordinat GPS harus diisi.',
    'longitude.required' => 'Koordinat GPS harus diisi.',
    'city.required' => 'Kota harus diisi.',
]);


        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update location
        $item->location->update([
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'city' => $request->city,
            'province' => $request->province,
        ]);

        // Handle photos
        $existingPhotos = $request->existing_photos ?? [];
        $newPhotos = [];

        // Process new photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/items', $photoName);
                $newPhotos[] = $photoName;
            }
        }

        // Delete removed photos
        if ($item->photos) {
            foreach ($item->photos as $oldPhoto) {
                if (!in_array($oldPhoto, $existingPhotos)) {
                    Storage::delete('public/items/' . $oldPhoto);
                }
            }
        }

        // Combine existing and new photos
        $allPhotos = array_merge($existingPhotos, $newPhotos);

        // Update item
        $item->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'event_date' => $request->event_date,
            'photos' => $allPhotos,
        ]);

        return redirect()->route('items.show', $item->id)
            ->with('success', 'Item berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if (!$item->canBeDeletedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat menghapus item ini.');
        }

        // Delete photos
        if ($item->photos) {
            foreach ($item->photos as $photo) {
                Storage::delete('public/items/' . $photo);
            }
        }

        // Delete location
        $item->location->delete();

        // Delete item
        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'Item berhasil dihapus!');
    }

    public function myItems()
    {
        $items = Auth::user()->items()
            ->with(['category', 'location'])
            ->latest()
            ->paginate(12);

        return view('items.my-items', compact('items'));
    }

    public function markAsResolved($id)
    {
        $item = Item::findOrFail($id);

        if (!$item->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat mengubah status item ini.');
        }

        $item->markAsResolved();

        return back()->with('success', 'Item telah ditandai sebagai selesai!');
    }

    public function toggleActive($id)
    {
        $item = Item::findOrFail($id);

        if (!$item->canBeEditedBy(Auth::user())) {
            abort(403, 'Anda tidak dapat mengubah status item ini.');
        }

        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Item berhasil {$status}!");
    }
}