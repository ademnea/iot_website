<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class publications extends Model
{
    protected $table = 'publications';
    protected $casts = [
        'date_published' => 'datetime',
    ];
    use HasFactory;
}
