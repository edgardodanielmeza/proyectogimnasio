<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReglaAcceso extends Model
{
    use HasFactory;

    protected $table = 'reglas_acceso';

    protected $fillable = [
        'sucursal_id',
        'tipo_membresia_id',
        'dia_semana',
        'hora_desde',
        'hora_hasta',
        'descripcion',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function tipoMembresia(): BelongsTo
    {
        return $this->belongsTo(TipoMembresia::class);
    }
}
