<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTag extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tag_id',
        'document_type',
        'document_id',
        'tagged_by'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($documentTag) {
            if (auth()->check()) {
                $documentTag->tagged_by = auth()->id();
            }
            $documentTag->created_at = now();
        });
    }

    /**
     * Get the tag
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the user who tagged
     */
    public function tagger()
    {
        return $this->belongsTo(User::class, 'tagged_by');
    }

    /**
     * Get the document (polymorphic)
     */
    public function document()
    {
        return $this->morphTo('document', 'document_type', 'document_id');
    }
}
