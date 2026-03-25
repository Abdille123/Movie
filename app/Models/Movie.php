<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'tagline',
        'synopsis',
        'genre',
        'release_year',
        'runtime_minutes',
        'age_rating',
        'critic_score',
        'audience_score',
        'tone',
        'featured',
    ];

    protected $casts = [
        'featured' => 'boolean',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class)->orderBy('starts_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function shortSynopsis(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->synopsis)->limit(135)->value(),
        );
    }
}
