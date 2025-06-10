<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispositivoControlAcceso extends Model
{
    use HasFactory;

    protected $table = 'dispositivos_control_acceso';

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'tipo',
        'identificador_dispositivo',
        'estado',
        'ip_address',
        'puerto',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function eventosAcceso(): HasMany
    {
        return $this->hasMany(EventoAcceso::class);
    }
}
