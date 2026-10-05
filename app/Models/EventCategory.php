<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'color',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (EventCategory $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
