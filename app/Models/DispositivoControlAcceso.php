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
        'nombre',
        'tipo',
        'sucursal_id',
        'ip_address',
        'mac_address',
        'estado',
        'ultimo_heartbeat_at'
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class); // App\Models\Sucursal
    }

    public function eventosAcceso(): HasMany
    {
        return $this->hasMany(EventoAcceso::class); // App\Models\EventoAcceso
    }
}
