<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'nip',
        'id_divisi',
        'id_department',
        'id_jabatan',
        'valid_from',
        'valid_till',
        'is_active',
        'activation_key',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'valid_from' => 'date',
        'valid_till' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get user profile
     */
    public function profile()
    {
        return $this->hasOne(UsersProfile::class, 'id_user');
    }

    /**
     * Get user roles (through pivot)
     */
    public function userRoles()
    {
        return $this->hasMany(BaseUserRole::class, 'id_user');
    }

    /**
     * Get user department
     */
    public function department()
    {
        return $this->belongsTo(MasterDepartment::class, 'id_department');
    }

    /**
     * Get user jabatan
     */
    public function jabatan()
    {
        return $this->belongsTo(MasterJabatan::class, 'id_jabatan');
    }

    /**
     * Get roles directly
     */
    public function roles()
    {
        return $this->belongsToMany(BaseRole::class, 'base_user_roles', 'id_user', 'id_roles');
    }

    /**
     * Get user division
     */
    public function divisi()
    {
        return $this->belongsTo(MasterDivisi::class, 'id_divisi', 'id');
    }

    /**
     * Get current jabatan history
     */
    public function currentJabatanHistory()
    {
        return $this->hasOne(UserJabatanHistory::class, 'id_user')
            ->active()
            ->current()
            ->latest('valid_from');
    }

    /**
     * Get all jabatan history
     */
    public function jabatanHistories()
    {
        return $this->hasMany(UserJabatanHistory::class, 'id_user');
    }

    /**
     * Get file access requests made by user
     */
    public function fileAccessRequests()
    {
        return $this->hasMany(FileAccessRequest::class, 'id_user');
    }

    /**
     * Get audit logs for user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Get document versions uploaded by user
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(DocumentVersion::class, 'uploaded_by');
    }

    /**
     * Check if user has role
     */
    public function hasRole($roleName)
    {
        return $this->roles()->where('roles_name', $roleName)->exists();
    }

    /**
     * Check if user has any of given roles
     */
    public function hasAnyRole(array $roleNames)
    {
        return $this->roles()->whereIn('roles_name', $roleNames)->exists();
    }

    /**
     * Check if user has access to menu
     */
    public function hasMenuAccess($menuId)
    {
        return $this->roles()->whereHas('privileges', function ($query) use ($menuId) {
            $query->where('id_menu', $menuId);
        })->exists();
    }

    /**
     * Check if user has specific function on menu
     */
    public function hasMenuFunction($menuId, $functionId)
    {
        return $this->roles()->whereHas('privileges', function ($query) use ($menuId, $functionId) {
            $query->where('id_menu', $menuId)
                ->where('id_function', $functionId);
        })->exists();
    }

    /**
     * Get all allowed menu IDs
     */
    public function getAllowedMenuIds()
    {
        $menuIds = [];
        foreach ($this->roles as $role) {
            $menuIds = array_merge($menuIds, $role->getAllowedMenuIds());
        }
        return array_unique($menuIds);
    }

    /**
     * Check if user has access to division
     */
    public function hasDivisionAccess($divisiId)
    {
        // Global access role
        if ($this->roles()->where('access_scope', 'global')->exists()) {
            return true;
        }

        // User's own division
        if ($this->id_divisi == $divisiId) {
            return true;
        }

        // Direct division role
        if ($this->roles()->where('id_divisi', $divisiId)->exists()) {
            return true;
        }

        // Role division access
        return $this->roles()->whereHas('divisionAccess', function ($query) use ($divisiId) {
            $query->where('id_divisi', $divisiId);
        })->exists();
    }

    /**
     * Get divisions user can access
     */
    public function getAccessibleDivisions()
    {
        if ($this->roles()->where('access_scope', 'global')->exists()) {
            return MasterDivisi::all();
        }

        $divisionIds = [];

        // User's own division
        if ($this->id_divisi) {
            $divisionIds[] = $this->id_divisi;
        }

        // Direct division roles
        $divisionIds = array_merge(
            $divisionIds,
            $this->roles()->whereNotNull('id_divisi')->pluck('id_divisi')->toArray()
        );

        // Role division access
        foreach ($this->roles as $role) {
            $divisionIds = array_merge(
                $divisionIds,
                $role->divisionAccess()->pluck('id_divisi')->toArray()
            );
        }

        return MasterDivisi::whereIn('id', array_unique($divisionIds))->get();
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->roles()->where('roles_name', 'Super Admin')->exists() ||
            $this->roles()->where('access_scope', 'global')->exists();
    }

    /**
     * Check if user is division admin
     */
    public function isDivisionAdmin($divisiId = null)
    {
        $query = $this->roles()->where('roles_name', 'like', '%Admin%');

        if ($divisiId) {
            $query->where('id_divisi', $divisiId);
        }

        return $query->exists();
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for valid users (within valid date range)
     */
    public function scopeValid($query)
    {
        return $query->where('valid_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('valid_till')
                    ->orWhere('valid_till', '>=', now());
            });
    }
}
