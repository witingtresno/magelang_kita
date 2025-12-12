<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kuliner extends Model
{
    /** @use HasFactory<\Database\Factories\KulinerFactory> */
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
        'opening_hours',
        'price_range',
        'specialty_menu',
        'services',
        'gallery',
        'tags',
        'social_links',
        'is_featured',
    ];

    protected $casts = [
        'opening_hours' => 'array',
        'specialty_menu' => 'array',
        'services' => 'array',
        'gallery' => 'array',
        'tags' => 'array',
        'social_links' => 'array',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    protected static function booted()
    {
        static::creating(function (Kuliner $kuliner) {
            if (empty($kuliner->slug)) {
                $kuliner->slug = Str::slug($kuliner->name . '-' . Str::random(4));
            }
        });
    }
}
