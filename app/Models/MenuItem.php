<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'parent_id',
        'label',
        'url',
        'location',
        'footer_column',
        'open_in_new_tab',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    public function scopeNavbar($query)
    {
        return $query->where('location', 'navbar')->whereNull('parent_id');
    }

    public function scopeFooter($query)
    {
        return $query->where('location', 'footer');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
