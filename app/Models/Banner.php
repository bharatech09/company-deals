<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
   protected $fillable = [
        'image',
        'title',
        'short_description',
    ];
}
