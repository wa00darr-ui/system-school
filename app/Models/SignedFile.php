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

    /**
     * Types that are safe to render inline in the browser.
     *
     * @var list<string>
     */
    public const PREVIEWABLE_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ];

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

    public function isPreviewable(): bool
    {
        return in_array(
            $this->mime_type,
            self::PREVIEWABLE_MIME_TYPES,
            true
        );
    }
}
