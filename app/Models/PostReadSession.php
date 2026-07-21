<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReadSession extends Model
{
    protected $fillable = ['post_id', 'user_id', 'duration_seconds'];

    protected function casts(): array
    {
        return ['duration_seconds' => 'integer'];
    }
}
