<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'amount',
        'status',
        'issued_at',
        'due_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issued_at' => 'date',
            'due_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'paid' => 'Payée',
            'pending' => 'En attente',
            'overdue' => 'En retard',
            default => $this->status,
        };
    }

    public static function nextInvoiceNumber(): string
    {
        $last = static::orderByDesc('id')->value('invoice_number');
        $number = $last ? ((int) substr($last, 4)) + 1 : 1;

        return 'INV-' . str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }
}
