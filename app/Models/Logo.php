<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    protected $fillable = [
        'seccion',
        'path',
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
