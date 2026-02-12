<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
            if (auth()->check()) {
                $tag->created_by = auth()->id();
            }
        });
    }

    /**
     * Relasi ke user yang membuat tag
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all document tags using this tag
     */
    public function documentTags()
    {
        return $this->hasMany(DocumentTag::class, 'tag_id');
    }

    /**
     * Hitung jumlah dokumen dengan tag ini
     */
    public function getDocumentsCountAttribute()
    {
        return $this->documentTags()->count();
    }
}
