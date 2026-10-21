<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prototypes extends Model
{
    protected $table = 'prototypes';
    use HasFactory;

    // Photos are stored one per row in prototype_photos.
    public function photos()
    {
        return $this->hasMany(prototype_photos::class, 'prototype_id')->orderBy('id');
    }

    // The first photo's file name (or null), for list cards that show one picture.
    public function getCoverAttribute(): ?string
    {
        return $this->photos->first()?->photo;
    }
}
