<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image_path',
        'views',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'views' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (empty($post->slug) && ! empty($post->title)) {
                $post->slug = Str::slug($post->title) . '-' . Str::random(5);
            }
            if ($post->status === 'published' && empty($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(PostBookmark::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(PostRating::class);
    }

    public function readSessions(): HasMany
    {
        return $this->hasMany(PostReadSession::class);
    }

    public function isLikedBy(?User $user): bool
    {
        return $user && $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isBookmarkedBy(?User $user): bool
    {
        return $user && $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function ratingBy(?User $user): ?int
    {
        if (! $user) {
            return null;
        }

        return $this->ratings()->where('user_id', $user->id)->value('rating');
    }

    public function averageRating(): float
    {
        return round((float) $this->ratings()->avg('rating'), 1);
    }

    public function averageReadSeconds(): int
    {
        return (int) round($this->readSessions()->avg('duration_seconds') ?? 0);
    }

    public function averageReadLabel(): string
    {
        $seconds = $this->averageReadSeconds();

        if ($seconds <= 0) {
            return '—';
        }

        if ($seconds < 60) {
            return "{$seconds} s";
        }

        return round($seconds / 60, 1) . ' min';
    }

    public function estimatedReadingTime(): int
    {
        $words = str_word_count(strip_tags($this->content));

        return max(1, (int) ceil($words / 200)); // ~200 mots/minute
    }

    public function coverImageUrl(): ?string
    {
        return $this->cover_image_path ? Storage::disk('public')->url($this->cover_image_path) : null;
    }

    public function statusLabel(): string
    {
        return $this->status === 'published' ? 'Publié' : 'Brouillon';
    }
}
