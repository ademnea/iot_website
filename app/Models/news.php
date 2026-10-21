<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class news extends Model
{    
    protected $table = 'events';
    use HasFactory;

    // Photos are stored one per row in event_photos.
    public function photos()
    {
        return $this->hasMany(event_photos::class, 'event_id')->orderBy('id');
    }

    // The first photo's file name (or null), for list cards that show one picture.
    public function getCoverAttribute(): ?string
    {
        return $this->photos->first()?->photo;
    }
}
