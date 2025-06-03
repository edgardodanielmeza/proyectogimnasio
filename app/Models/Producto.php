<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_venta',
        'stock_actual',
        'categoria_producto_id',
    ];

    protected $casts = [
        'precio_venta' => 'decimal:2',
    ];

    public function categoriaProducto(): BelongsTo
    {
        return $this->belongsTo(CategoriaProducto::class);
    }

    public function ventasDetalle(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }
}
