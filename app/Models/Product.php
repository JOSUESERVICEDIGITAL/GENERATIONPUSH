<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'is_free',
        'price',
        'image_path',
        'file_path',
        'video_url',
        'stock',
        'sales',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'sales' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->slug) && ! empty($product->title)) {
                $product->slug = Str::slug($product->title) . '-' . Str::random(5);
            }
        });
    }

    public function imageUrl(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::disk('public')->url($this->file_path) : null;
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'book' => 'Livre',
            'video' => 'Masterclass vidéo',
            'usb_key' => 'Clé USB',
            default => $this->type,
        };
    }

    public function statusLabel(): string
    {
        return $this->status === 'published' ? 'Publié' : 'Brouillon';
    }

    public function priceLabel(): string
    {
        return $this->is_free ? 'Gratuit' : number_format((float) $this->price, 2) . ' $';
    }
}
