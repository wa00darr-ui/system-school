<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'recipient_name',
        'recipient_phone',
        'recipient_email',
        'original_file',
        'signed_file',
        'access_token',
        'status',
        'signed_at',
        'completed_at',
        'ip_address',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($document) {
            $document->access_token = Str::random(64);
        });
    }
}
