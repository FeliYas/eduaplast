<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    protected $fillable = [
        'path',
        'titulo',
        'descripcion',
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
