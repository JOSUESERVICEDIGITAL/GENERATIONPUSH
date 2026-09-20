<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'title',
        'price',
        'quantity',
        'fulfillment_type',
        'digital_file_path',
        'digital_access_granted_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'digital_access_granted_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TYPE DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    public function fulfillmentTypeLabel(): string
    {
        return match ($this->fulfillment_type) {
            'physical' => 'Version physique',
            'digital' => 'Version numérique',
            default => ucfirst((string) $this->fulfillment_type),
        };
    }

    public function isPhysical(): bool
    {
        return $this->fulfillment_type === 'physical';
    }

    public function isDigital(): bool
    {
        return $this->fulfillment_type === 'digital';
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENU NUMÉRIQUE
    |--------------------------------------------------------------------------
    */

    public function hasDigitalFile(): bool
    {
        return $this->isDigital()
            && filled($this->digital_file_path);
    }

    public function digitalFileUrl(): ?string
    {
        if (! $this->hasDigitalFile()) {
            return null;
        }

        return Storage::disk('public')->url(
            $this->digital_file_path
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRIX
    |--------------------------------------------------------------------------
    */

    public function subtotal(): float
    {
        return (float) $this->price * $this->quantity;
    }
}
