<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matriceria extends Model
{
    protected $fillable = [
        'path',
        'titulo',
        'descripcion',
    ];
}
