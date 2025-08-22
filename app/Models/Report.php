<?php
// app/Models/Report.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'reporter_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByReason($query, $reason)
    {
        return $query->where('reason', $reason);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Helper Methods
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Ditinjau',
            'resolved' => 'Diselesaikan',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'reviewed' => 'info',
            'resolved' => 'success',
            'rejected' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getReasonLabelAttribute()
    {
        $reasons = [
            'spam' => 'Spam',
            'fake' => 'Palsu/Bohong',
            'inappropriate' => 'Tidak Pantas',
            'scam' => 'Penipuan',
            'duplicate' => 'Duplikat',
            'other' => 'Lainnya',
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y H:i');
    }

    public function getCreatedAtHumanAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getReviewedAtFormattedAttribute()
    {
        return $this->reviewed_at ? $this->reviewed_at->format('d M Y H:i') : null;
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isReviewed()
    {
        return $this->status === 'reviewed';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function review($adminId, $note = null)
    {
        $this->update([
            'status' => 'reviewed',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    public function resolve($adminId, $note = null)
    {
        $this->update([
            'status' => 'resolved',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    public function reject($adminId, $note = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_note' => $note,
        ]);
    }

    // Static Methods
    public static function getReasonOptions()
    {
        return [
            'spam' => 'Spam',
            'fake' => 'Palsu/Bohong',
            'inappropriate' => 'Tidak Pantas',
            'scam' => 'Penipuan',
            'duplicate' => 'Duplikat',
            'other' => 'Lainnya',
        ];
    }
}