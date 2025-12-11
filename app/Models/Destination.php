<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\SlugOptions;


class Destination extends Model
{
    /** @use HasFactory<\Database\Factories\DestinationFactory> */
    use HasFactory;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    protected $fillable = [
        'name',
        'slug',
        'category',
        'type',
        'title',
        'subtitle',
        'deskripsi',
        'address',
        'latitude',
        'longitude',
        'contact',
        'is_rekomend',
        'opening_hours',
        'gallery',
        'tags',
    ];

    protected $casts = [
        'is_rekomend' => 'boolean',
        'opening_hours' => 'array',
        'gallery' => 'array',
        'tags' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];
}
