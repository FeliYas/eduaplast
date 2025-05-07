<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'titulo',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
