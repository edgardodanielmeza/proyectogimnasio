<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoAcceso extends Model
{
    use HasFactory;

    protected $table = 'eventos_acceso';

    protected $fillable = [
        'miembro_id',
        'dispositivo_control_acceso_id',
        'sucursal_id',
        'fecha_hora',
        'tipo_acceso_intentado',
        'resultado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function miembro(): BelongsTo
    {
        return $this->belongsTo(Miembro::class);
    }

    public function dispositivoControlAcceso(): BelongsTo
    {
        return $this->belongsTo(DispositivoControlAcceso::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
