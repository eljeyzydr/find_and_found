<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Statistics for landing page
        $stats = [
            'total_items' => Item::count(),
            'resolved_items' => Item::resolved()->count(),
            'active_users' => User::active()->count(),
            'cities_covered' => Location::distinct('city')->count('city'),
            'success_rate' => Item::count() > 0 ? 
                round((Item::resolved()->count() / Item::count()) * 100, 1) : 0,
        ];

        // Recent items
        $recent_lost = Item::with(['category', 'location'])
            ->lost()
            ->active()
            ->latest()
            ->take(6)
            ->get();

        $recent_found = Item::with(['category', 'location'])
            ->found()
            ->active()
            ->latest()
            ->take(6)
            ->get();

        // Popular categories
        $popular_categories = Category::withCount(['items' => function($query) {
                $query->active();
            }])
            ->active()
            ->orderBy('items_count', 'desc')
            ->take(8)
            ->get();

        // Success stories (resolved items)
        $success_stories = Item::with(['category', 'location', 'user'])
            ->resolved()
            ->latest('resolved_at')
            ->take(3)
            ->get();

        return view('home', compact(
            'stats',
            'recent_lost',
            'recent_found',
            'popular_categories',
            'success_stories'
        ));
    }

    public function search(Request $request)
    {
        $query = Item::with(['user', 'category', 'location'])
            ->active();

        // Basic search
        if ($request->q) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%");
            });
        }

        // Advanced filters
        if ($request->status && in_array($request->status, ['lost', 'found'])) {
            $query->where('status', $request->status);
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->city) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('city', 'like', "%{$request->city}%");
            });
        }

        if ($request->date_from) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        // Location-based search
        if ($request->latitude && $request->longitude && $request->radius) {
            $query->whereHas('location', function($q) use ($request) {
                $q->withinRadius($request->latitude, $request->longitude, $request->radius);
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'newest':
                $query->latest('created_at');
                break;
            case 'oldest':
                $query->oldest('created_at');
                break;
            case 'event_date_desc':
                $query->latest('event_date');
                break;
            case 'event_date_asc':
                $query->oldest('event_date');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            default:
                $query->latest('created_at');
        }

        $items = $query->paginate(12)->withQueryString();
        $categories = Category::active()->get();

        return view('search', compact('items', 'categories'));
    }

    public function browse()
    {
        $categories = Category::withCount(['items' => function($query) {
                $query->active();
            }])
            ->active()
            ->orderBy('name')
            ->get();

        $recent_items = Item::with(['category', 'location'])
            ->active()
            ->latest()
            ->take(12)
            ->get();

        $stats = [
            'total_lost' => Item::lost()->active()->count(),
            'total_found' => Item::found()->active()->count(),
            'total_resolved' => Item::resolved()->count(),
        ];

        return view('browse', compact('categories', 'recent_items', 'stats'));
    }

    public function about()
    {
        $stats = [
            'total_items' => Item::count(),
            'resolved_items' => Item::resolved()->count(),
            'active_users' => User::active()->count(),
            'cities_covered' => Location::distinct('city')->count('city'),
        ];

        return view('about', compact('stats'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'subject.required' => 'Subjek harus diisi.',
            'message.required' => 'Pesan harus diisi.',
            'message.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Send email to admin or save to database
        // For now, just return success message

        return back()->with('success', 'Terima kasih! Pesan Anda telah dikirim. Kami akan merespons sesegera mungkin.');
    }

    public function faq()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara melaporkan barang hilang?',
                'answer' => 'Anda perlu mendaftar akun terlebih dahulu, kemudian klik "Laporkan Barang Hilang" dan isi formulir dengan lengkap termasuk foto dan lokasi.'
            ],
            [
                'question' => 'Apakah ada biaya untuk menggunakan layanan ini?',
                'answer' => 'Tidak, layanan Find & Found sepenuhnya gratis untuk semua pengguna.'
            ],
            [
                'question' => 'Bagaimana cara menghubungi orang yang menemukan barang saya?',
                'answer' => 'Anda dapat menggunakan fitur chat internal di aplikasi atau meminta admin untuk memberikan kontak jika diperlukan.'
            ],
            [
                'question' => 'Berapa lama postingan akan tetap aktif?',
                'answer' => 'Postingan akan tetap aktif sampai Anda menandainya sebagai selesai atau admin menonaktifkannya.'
            ],
            [
                'question' => 'Bagaimana jika ada yang menyalahgunakan platform ini?',
                'answer' => 'Anda dapat melaporkan postingan atau pengguna yang mencurigakan melalui fitur "Laporkan" yang tersedia.'
            ],
        ];

        return view('faq', compact('faqs'));
    }

    public function terms()
    {
        return view('terms');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function map()
    {
        $categories = Category::active()->get();
        
        return view('map', compact('categories'));
    }
}