<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'movie_id',
        'author_name',
        'rating',
        'comment',
        'favourite_scene',
        'would_rewatch',
    ];

    protected $casts = [
        'would_rewatch' => 'boolean',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }
}
