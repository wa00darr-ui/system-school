<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

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

    public function signedFiles(): HasMany
    {
        return $this->hasMany(SignedFile::class);
    }

    /**
     * @param  array{q?: string|null, category?: string|null, status?: string|null, from?: string|null, to?: string|null}  $filters
     */
    #[Scope]
    protected function filtered(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['q'] ?? null, function (Builder $query, string $search): void {
                $escaped = addcslashes($search, '%_\\');

                $query->where(function (Builder $query) use ($escaped): void {
                    $query->where('title', 'like', '%'.$escaped.'%')
                        ->orWhere('recipient_name', 'like', '%'.$escaped.'%')
                        ->orWhere('recipient_email', 'like', '%'.$escaped.'%')
                        ->orWhere('recipient_phone', 'like', '%'.$escaped.'%');
                });
            })
            ->when($filters['category'] ?? null, function (Builder $query, string $category): void {
                $query->where('category', $category);
            })
            ->when($filters['status'] ?? null, function (Builder $query, string $status): void {
                $query->where('status', $status);
            })
            ->when($filters['from'] ?? null, function (Builder $query, string $from): void {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($filters['to'] ?? null, function (Builder $query, string $to): void {
                $query->whereDate('created_at', '<=', $to);
            });
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'teachers' => 'المعلمات',
            'administrators' => 'الإداريات',
            'parents' => 'أولياء الأمور',
            default => $this->category,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'بانتظار الإجراء',
            'completed' => 'مكتمل',
            'signed' => 'تم التوقيع',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'completed' => 'bg-success',
            'signed' => 'bg-info',
            default => 'bg-secondary',
        };
    }
}
