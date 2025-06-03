<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'miembro_id',
        'fecha_venta',
        'total_venta',
        'metodo_pago',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'total_venta' => 'decimal:2',
    ];

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(Miembro::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(VentaDetalle::class);
    }
}
