<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'location_id',
        'title',
        'description',
        'status',
        'photos',
        'event_date',
        'is_active',
        'is_resolved',
        'resolved_at',
        'views_count',
    ];

    protected $casts = [
        'photos' => 'array',
        'event_date' => 'date',
        'resolved_at' => 'datetime',
        'is_active' => 'boolean',
        'is_resolved' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }

    public function scopeFound($query)
    {
        return $query->where('status', 'found');
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation($query, $city)
    {
        return $query->whereHas('location', function($q) use ($city) {
            $q->where('city', 'like', "%{$city}%");
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Helper Methods
    public function isLost()
    {
        return $this->status === 'lost';
    }

    public function isFound()
    {
        return $this->status === 'found';
    }

    public function getStatusLabelAttribute()
    {
        return $this->status === 'lost' ? 'Hilang' : 'Ditemukan';
    }

    public function getStatusColorAttribute()
    {
        return $this->status === 'lost' ? 'danger' : 'success';
    }

    // ✅ FIXED: Photo attributes dengan pengecekan yang benar
    public function getFirstPhotoAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos) && count($this->photos) > 0) {
            // Cek apakah file benar-benar ada
            $photoPath = 'items/' . $this->photos[0];
            if (Storage::disk('public')->exists($photoPath)) {
                return asset('storage/' . $photoPath);
            }
        }
        
        return asset('images/no-image.png');
    }

    public function getPhotoUrlsAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return array_map(function($photo) {
                $photoPath = 'items/' . $photo;
                if (Storage::disk('public')->exists($photoPath)) {
                    return asset('storage/' . $photoPath);
                }
                return asset('images/no-image.png');
            }, $this->photos);
        }
        return [];
    }

    // Helper method untuk cek foto exists
    public function getValidPhotosAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return array_filter($this->photos, function($photo) {
                return Storage::disk('public')->exists('items/' . $photo);
            });
        }
        return [];
    }

    public function getEventDateFormattedAttribute()
    {
        return $this->event_date->format('d M Y');
    }

    public function getEventDateHumanAttribute()
    {
        return $this->event_date->diffForHumans();
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function markAsResolved()
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);
    }

    public function getCommentsCount()
    {
        return $this->comments()->count();
    }

    public function getReportsCount()
    {
        return $this->reports()->count();
    }

    public function canBeEditedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->isAdmin());
    }

    public function canBeDeletedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->isAdmin());
    }
}