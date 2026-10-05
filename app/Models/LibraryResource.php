<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryResource extends Model
{
    use HasFactory;

    protected $table = 'library_resources';

    protected $fillable = [
        'product_id',
        'title',
        'slug',
        'author',
        'subtitle',
        'description',

        'cover_image',
        'showcase_image',

        'isbn',
        'publisher',
        'publication_date',
        'pages',

        'price',
        'promotional_price',
        'format',
        'stock',

        'value_description',
        'discover_content',

        'is_bestseller',
        'show_on_podium',
        'display_order',

        'status',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',

            'pages' => 'integer',

            'price' => 'decimal:2',
            'promotional_price' => 'decimal:2',

            'stock' => 'integer',

            'is_bestseller' => 'boolean',
            'show_on_podium' => 'boolean',

            'display_order' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (LibraryResource $book) {
            if (blank($book->slug)) {
                $book->slug = static::generateUniqueSlug($book->title);
            }
        });

        static::updating(function (LibraryResource $book) {
            if (
                blank($book->slug) ||
                ($book->isDirty('title') && ! $book->isDirty('slug'))
            ) {
                $book->slug = static::generateUniqueSlug(
                    $book->title,
                    $book->id
                );
            }
        });

        /*
        |--------------------------------------------------------------------------
        | UN SEUL LIVRE SUR LE PLATEAU
        |--------------------------------------------------------------------------
        */

        static::saved(function (LibraryResource $book) {
            if ($book->show_on_podium) {
                static::query()
                    ->whereKeyNot($book->id)
                    ->where('show_on_podium', true)
                    ->update([
                        'show_on_podium' => false,
                    ]);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeOnPodium($query)
    {
        return $query->where('show_on_podium', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    /*
    |--------------------------------------------------------------------------
    | SLUG
    |--------------------------------------------------------------------------
    */

    public static function generateUniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 2;

        while (
            static::query()
            ->when(
                $ignoreId,
                fn($query) => $query->whereKeyNot($ignoreId)
            )
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | VISUELS
    |--------------------------------------------------------------------------
    */

    public function coverUrl(): ?string
    {
        return $this->cover_image
            ? Storage::disk('public')->url($this->cover_image)
            : null;
    }

    public function showcaseUrl(): ?string
    {
        return $this->showcase_image
            ? Storage::disk('public')->url($this->showcase_image)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | PRIX
    |--------------------------------------------------------------------------
    */

    public function hasPromotion(): bool
    {
        return $this->promotional_price !== null
            && (float) $this->promotional_price < (float) $this->price;
    }

    public function currentPrice(): ?float
    {
        if ($this->price === null) {
            return null;
        }

        return $this->hasPromotion()
            ? (float) $this->promotional_price
            : (float) $this->price;
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT
    |--------------------------------------------------------------------------
    */

    public function formatLabel(): string
    {
        return match ($this->format) {
            'physical' => 'Livre physique',
            'digital' => 'Livre numérique',
            'both' => 'Physique & numérique',
            default => 'Livre',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | STATUT
    |--------------------------------------------------------------------------
    */

    public function statusLabel(): string
    {
        return match ($this->status) {
            'published' => 'Publié',
            'draft' => 'Brouillon',
            default => ucfirst((string) $this->status),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK
    |--------------------------------------------------------------------------
    */

    public function isAvailable(): bool
    {
        if ($this->format === 'digital') {
            return true;
        }

        return $this->stock === null || $this->stock > 0;
    }
}
