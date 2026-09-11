<?php

namespace App\Models;

use Database\Factories\SignedFileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignedFile extends Model
{
    /** @use HasFactory<SignedFileFactory> */
    use HasFactory;

    protected $fillable = [
        'original_name',
        'path',
        'mime_type',
        'size',
        'signature_name',
        'ip_address',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
