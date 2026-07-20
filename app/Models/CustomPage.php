<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (CustomPage $page) {
            if (empty($page->slug) && ! empty($page->title)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function statusLabel(): string
    {
        return $this->status === 'published' ? 'Publiée' : 'Brouillon';
    }
}
