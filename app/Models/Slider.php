<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'titulo',
        'descripcion',
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
