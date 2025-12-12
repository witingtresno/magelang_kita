<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Place extends Model
{
    /** @use HasFactory<\Database\Factories\PlaceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'address',
        'latitude',
        'longitude',
        'contact',
        'ticket_price',
        'opening_hours',
        'gallery',
        'tags',
        'is_featured'
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'gallery' => 'array',
        'tags' => 'array',
        'sosial_links' => 'array',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7'
    ];

    protected static function booted()
    {
        static::creating(function (Place $place) {
            if (empty($place->slug)) {
                $place->slug = Str::slug($place->name . '-' . Str::random(4));
            }
        });

        static::updating(function (Place $place) {
            if ($place->isDirty('name') && empty($place->slug)) {
                $place->slug = Str::slug($place->name . '-' . Str::random(4));
            }
        });
    }
}
