<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersProfile extends Model
{
    use SoftDeletes;

    protected $table = 'users_profile';

    protected $fillable = [
        'id_user',
        'nip',
        'divisi',
        'department',
        'employee_status',
        'join_date',
        'npwp',
        'ktp',
        'emergency_contact_name',
        'emergency_contact_phone',
        'blood_type',
        'religion',
        'marital_status',
        'education_level',
        'signature_file',
        'first_name',
        'last_name',
        'photo',
        'gender',
        'birth_date',
        'id_vilages',
        'address',
        'country',
        'city',
        'poscode',
        'phone',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
        'website',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'join_date' => 'date',
        'birth_date' => 'date',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get photo URL
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/profiles/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Get signature URL
     */
    public function getSignatureUrlAttribute()
    {
        if ($this->signature_file) {
            return asset('storage/signatures/' . $this->signature_file);
        }
        return null;
    }
}
