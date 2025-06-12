<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nosotro extends Model
{
    protected $fillable = [
        'path',
        'titulo',
        'descripcion',
        'imagen1',
        'titulo1',
        'descripcion1',
        'imagen2',
        'titulo2',
        'descripcion2',
        'imagen3',
        'titulo3',
        'descripcion3'
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
