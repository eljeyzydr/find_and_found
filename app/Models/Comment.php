<?php
// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'content',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    // Helper Methods
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function canBeEditedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->isAdmin());
    }

    public function canBeDeletedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->isAdmin());
    }

    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    public function reject()
    {
        $this->update(['is_approved' => false]);
    }

    // Events
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($comment) {
            // Buat notifikasi untuk pemilik item
            if ($comment->item->user_id !== $comment->user_id) {
                Notification::create([
                    'user_id' => $comment->item->user_id,
                    'title' => 'Komentar Baru',
                    'message' => $comment->user->name . ' berkomentar pada item "' . $comment->item->title . '"',
                    'type' => 'comment',
                    'data' => [
                        'item_id' => $comment->item_id,
                        'comment_id' => $comment->id,
                        'commenter_name' => $comment->user->name,
                    ],
                ]);
            }
        });
    }
}