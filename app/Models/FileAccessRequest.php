<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileAccessRequest extends Model
{
    use SoftDeletes;

    protected $table = 'file_access_requests';

    protected $fillable = [
        'id_user',
        'document_type',
        'document_id',
        'id_divisi',
        'status',
        'permissions',
        'valid_till',
        'download_limit',
        'download_count',
        'request_reason',
        'response_reason',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'valid_till' => 'datetime',
        'permissions' => 'array',
    ];

    /**
     * Check if request has specific permission
     */
    public function hasPermission($permission)
    {
        if (empty($this->permissions)) {
            // Null/Empty permissions = Full Access (Backward Compatibility)
            // Or strictly View Only? Plan said default to ['read'] or full for legacy.
            // Let's assume view implies read.
            // If checking 'read', always true if approved.
            if ($permission === 'read') return true;

            // For other permissions, if null, maybe allow download by default for legacy?
            // Let's implement strict: if nul, default to read + download for legacy/backward compat
            if ($permission === 'download') return true;

            return false;
        }

        return in_array($permission, $this->permissions);
    }

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the requester
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the division
     */
    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'id_divisi');
    }

    /**
     * Get the responder
     */
    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get the document (polymorphic)
     */
    public function document()
    {
        return $this->morphTo('document', 'document_type', 'document_id');
    }

    /**
     * Approve request
     */
    public function approve($userId, $reason = null, $permissions = [])
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'permissions' => $permissions,
            'responded_by' => $userId,
            'responded_at' => now(),
            'response_reason' => $reason,
        ]);
    }

    /**
     * Reject request
     */
    public function reject($userId, $reason)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'responded_by' => $userId,
            'responded_at' => now(),
            'response_reason' => $reason,
        ]);
    }

    /**
     * Revoke all access requests for a specific document
     */
    public static function revokeAccess($documentType, $documentId, $reason = 'Document updated')
    {
        // Find all approved requests for this document
        $requests = self::where('document_type', $documentType)
            ->where('document_id', $documentId)
            ->where('status', self::STATUS_APPROVED)
            ->get();

        foreach ($requests as $request) {
            $request->update([
                'status' => self::STATUS_EXPIRED,
                'response_reason' => $reason, // Update reason to explain expiration
                'responded_at' => now(), // Meaning expired at
            ]);
        }
    }

    /**
     * Scope pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope by division
     */
    public function scopeByDivision($query, $divisiId)
    {
        return $query->where('id_divisi', $divisiId);
    }

    /**
     * Scope by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_EXPIRED => 'Kadaluarsa',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'badge-warning',
            self::STATUS_APPROVED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_EXPIRED => 'badge-secondary',
        ];
        return $classes[$this->status] ?? 'badge-secondary';
    }

    /**
     * Check if request is valid (Time and Count)
     */
    public function isValid()
    {
        if ($this->status !== self::STATUS_APPROVED) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        if ($this->isDownloadLimitReached()) {
            return false;
        }

        return true;
    }

    /**
     * Check if request is expired
     */
    public function isExpired()
    {
        return $this->valid_till && now()->gt($this->valid_till);
    }

    /**
     * Check if download limit is reached
     */
    public function isDownloadLimitReached()
    {
        // Limit 0 or null implies unlimited in some systems, but here
        // usually if set, it is strict. If null, maybe unlimited?
        // Logic says: "jika batas download sudah mencapai batas".
        // If download_limit is null, assume unlimited.
        if ($this->download_limit && $this->download_count >= $this->download_limit) {
            return true;
        }
        return false;
    }

    /**
     * Increment download count
     */
    public function incrementDownload()
    {
        $this->increment('download_count');
    }
}
