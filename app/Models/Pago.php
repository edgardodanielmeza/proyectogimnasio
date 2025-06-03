<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'miembro_id',
        'membresia_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'referencia_pago',
        'factura_generada',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date',
        'factura_generada' => 'boolean',
    ];

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(Miembro::class);
    }

    public function membresia(): BelongsTo
    {
        return $this->belongsTo(Membresia::class);
    }
}
