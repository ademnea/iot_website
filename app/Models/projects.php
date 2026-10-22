<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    protected $table = 'projects';
    use HasFactory;

    // Photos are stored one per row in project_photos.
    public function photos()
    {
        return $this->hasMany(project_photos::class, 'project_id')->orderBy('id');
    }

    // Prototypes built in this project (deleted with it).
    public function prototypes()
    {
        return $this->hasMany(prototypes::class, 'project_id');
    }

    // The first photo's file name (or null), for list cards that show one picture.
    public function getCoverAttribute(): ?string
    {
        return $this->photos->first()?->photo;
    }
}
