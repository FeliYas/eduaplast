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
}
