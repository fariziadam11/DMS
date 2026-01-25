<?php

namespace App\Models\Traits;

use App\Models\DocumentVersion;
use App\Models\DocumentWorkflow;
use App\Models\MasterDivisi;
use App\Models\User;

trait HasDocumentFeatures
{
    /**
     * Boot the trait
     */
    protected static function bootHasDocumentFeatures()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
            if (!$model->version) {
                $model->version = 1;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    /**
     * Get the division
     */
    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'id_divisi');
    }

    /**
     * Get the creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get document versions
     */
    public function versions()
    {
        return $this->morphMany(DocumentVersion::class, 'document', 'document_type', 'document_id');
    }

    /**
     * Get current version
     */
    public function currentVersion()
    {
        return $this->morphOne(DocumentVersion::class, 'document', 'document_type', 'document_id')
            ->where('is_current', true);
    }

    /**
     * Get workflow
     */
    public function workflow()
    {
        return $this->morphOne(DocumentWorkflow::class, 'document', 'document_type', 'document_id');
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file) {
            return asset('storage/' . $this->getStoragePath() . '/' . $this->file);
        }
        return null;
    }

    /**
     * Get storage path for this document type
     */
    protected function getStoragePath()
    {
        return 'documents/' . strtolower(class_basename($this));
    }

    /**
     * Create new version when file changes
     */
    public function createVersion($file, $fileName, $fileSize, $notes = null)
    {
        return DocumentVersion::createVersion(
            $this->getTable(),
            $this->id,
            $file,
            $fileName,
            $fileSize,
            auth()->id(),
            $notes
        );
    }

    /**
     * Check if document is classified as secret
     */
    public function isSecret()
    {
        return $this->sifat_dokumen === 'Rahasia';
    }

    /**
     * Check if document is classified as internal
     */
    public function isInternal()
    {
        return $this->sifat_dokumen === 'Internal';
    }

    /**
     * Scope by classification
     */
    public function scopeByClassification($query, $classification)
    {
        return $query->where('sifat_dokumen', $classification);
    }

    /**
     * Scope secret documents only
     */
    public function scopeSecret($query)
    {
        return $query->where('sifat_dokumen', 'Rahasia');
    }

    /**
     * Scope public documents only
     */
    public function scopePublic($query)
    {
        return $query->where('sifat_dokumen', 'Umum');
    }

    /**
     * Scope by division
     */
    public function scopeByDivision($query, $divisiId)
    {
        return $query->where('id_divisi', $divisiId);
    }

    /**
     * Scope for accessible by user
     */
    public function scopeAccessibleByUser($query, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $query->whereRaw('1 = 0'); // Return empty
        }

        // Super admin can see all
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Get accessible divisions
        $accessibleDivisionIds = $user->getAccessibleDivisions()->pluck('id');

        return $query->where(function ($q) use ($accessibleDivisionIds, $userId) {
            // 1. Umum documents are visible to everyone
            $q->where('sifat_dokumen', 'Umum')

            // 2. Internal documents: visible if user in same division
              ->orWhere(function($subQ) use ($accessibleDivisionIds) {
                  $subQ->where('sifat_dokumen', 'Internal')
                       ->whereIn('id_divisi', $accessibleDivisionIds);
              })

            // 3. Creator can always access
              ->orWhere('created_by', $userId)

            // 4. Approved Access Request logic
              ->orWhereExists(function ($subQ) use ($userId) {
                    $subQ->select(\DB::raw(1))
                        ->from('file_access_requests')
                        ->whereColumn('file_access_requests.document_id', $this->getTable() . '.id')
                        ->where('file_access_requests.document_type', static::class)
                        ->where('file_access_requests.id_user', $userId)
                        ->where('file_access_requests.status', 'approved');
                });
        });
    }

    /**
     * Scope for global search
     */
    public function scopeGlobalSearch($query, $search)
    {
        $searchableFields = $this->getSearchableFields();

        return $query->where(function ($q) use ($search, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * Get searchable fields for this model
     * Override in child class to customize
     */
    protected function getSearchableFields()
    {
        // Define potential searchable fields
        $potentialFields = ['judul', 'nomor', 'perihal', 'keterangan', 'file_name', 'nama', 'uraian'];

        // Get actual columns from the table
        $tableColumns = \Illuminate\Support\Facades\Schema::getColumnListing($this->getTable());

        // Return only fields that actually exist in the table
        return array_intersect($potentialFields, $tableColumns);
    }

    /**
     * Check if user has access to file content (download/preview)
     */
    public function userHasFileAccess($userId)
    {
        $user = User::find($userId);
        if (!$user) return false;

        // Super Admin can access everything
        if ($user->isSuperAdmin()) return true;

        // Creator can always access
        if ($this->created_by == $userId) return true;

        // Check for specific approved access request
        $hasApprovedRequest = \App\Models\FileAccessRequest::where('document_type', $this->getTable())
            ->where('document_id', $this->id)
            ->where('id_user', $userId)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedRequest) return true;

        // Division Admin of the document's division can always access
        if ($user->isDivisionAdmin($this->id_divisi)) return true;

        // Logic based on Classification
        if ($this->sifat_dokumen === 'Umum') {
            // Umum is now Global (visible to all authenticated users)
            return true;
        }

        if ($this->sifat_dokumen === 'Internal') {
            // Internal: Only visible to division members (inherited via hasDivisionAccess)
            return $user->hasDivisionAccess($this->id_divisi);
        }

        // For 'Rahasia' documents
        if ($this->isSecret()) {
            // STRICT: Even same division staff cannot access unless they are admin or have approved request
            // So default is false here
            return false;
        }

        // Default fallback (should be covered above, but safe to restrict)
        return false;
    }

    /**
     * Get document info for search results
     */
    public function getSearchResultInfo()
    {
        return [
            'id' => $this->id,
            'type' => class_basename($this),
            'table' => $this->getTable(),
            'title' => $this->judul ?? $this->perihal ?? $this->nama ?? $this->file_name ?? 'Dokumen #' . $this->id,
            'division' => $this->divisi ? $this->divisi->nama_divisi : null,
            'classification' => $this->sifat_dokumen,
            'version' => $this->version,
            'created_at' => $this->created_at,
            'file_url' => $this->file_url,
        ];
    }
}
