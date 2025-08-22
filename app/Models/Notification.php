<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'is_read',
        'is_sent_email',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_sent_email' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper Methods
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function markEmailSent()
    {
        $this->update(['is_sent_email' => true]);
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'comment' => 'Komentar',
            'chat' => 'Chat',
            'match' => 'Match',
            'system' => 'System',
            'report' => 'Laporan',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            'comment' => 'fas fa-comment',
            'chat' => 'fas fa-comments',
            'match' => 'fas fa-heart',
            'system' => 'fas fa-bell',
            'report' => 'fas fa-flag',
        ];

        return $icons[$this->type] ?? 'fas fa-bell';
    }

    public function getTypeColorAttribute()
    {
        $colors = [
            'comment' => 'primary',
            'chat' => 'success',
            'match' => 'danger',
            'system' => 'info',
            'report' => 'warning',
        ];

        return $colors[$this->type] ?? 'secondary';
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getActionUrlAttribute()
    {
        if (!$this->data) {
            return '#';
        }

        switch ($this->type) {
            case 'comment':
                return route('items.show', $this->data['item_id'] ?? 1) . '#comments';
            case 'chat':
                return route('chats.show', $this->data['sender_id'] ?? 1);
            case 'match':
                return route('items.show', $this->data['item_id'] ?? 1);
            case 'report':
                return route('admin.reports.show', $this->data['report_id'] ?? 1);
            default:
                return '#';
        }
    }

    // Static Methods
    public static function createForUser($userId, $title, $message, $type, $data = [])
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public static function markAllAsReadForUser($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}