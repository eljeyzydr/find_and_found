<?php
// app/Models/Chat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'item_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
        })->orWhere(function($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', $userId)->orWhere('receiver_id', $userId);
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
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getOtherUser($currentUserId)
    {
        return $this->sender_id === $currentUserId ? $this->receiver : $this->sender;
    }

    // Static Methods
    public static function getConversations($userId)
    {
        return self::select('chats.*')
            ->where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->orWhere('receiver_id', $userId);
            })
            ->with(['sender', 'receiver', 'item'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($chat) use ($userId) {
                // Group by other user ID
                return $chat->sender_id === $userId ? $chat->receiver_id : $chat->sender_id;
            })
            ->map(function($chats) {
                return $chats->first(); // Get the latest message for each conversation
            });
    }

    public static function getConversationWith($userId, $otherUserId)
    {
        return self::betweenUsers($userId, $otherUserId)
            ->with(['sender', 'receiver', 'item'])
            ->oldest()
            ->get();
    }

    // Events
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($chat) {
            // Buat notifikasi untuk penerima
            Notification::create([
                'user_id' => $chat->receiver_id,
                'title' => 'Pesan Baru',
                'message' => $chat->sender->name . ' mengirim pesan',
                'type' => 'chat',
                'data' => [
                    'chat_id' => $chat->id,
                    'sender_id' => $chat->sender_id,
                    'sender_name' => $chat->sender->name,
                    'item_id' => $chat->item_id,
                ],
            ]);
        });
    }
}