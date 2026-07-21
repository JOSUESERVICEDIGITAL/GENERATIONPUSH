<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRating extends Model
{
    protected $fillable = ['post_id', 'user_id', 'rating'];

    protected function casts(): array
    {
        return ['rating' => 'integer'];
    }
}
