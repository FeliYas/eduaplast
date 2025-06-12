<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Novedade extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'epigrafe',
        'titulo',
        'descripcion'
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
