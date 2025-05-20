<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sectore extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'titulo',
    ];

    public function getPathAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
