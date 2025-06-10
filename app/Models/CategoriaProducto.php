<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaProducto extends Model
{
    use HasFactory;

    protected $table = 'categorias_producto';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
