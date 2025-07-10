<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'horario_atencion',
        'logo_path',
    ];
    public function users(): HasMany // Relación con usuarios (si un usuario pertenece a una sucursal)
    {
        return $this->hasMany(User::class);
    }

    public function dispositivosControlAcceso(): HasMany
    {
        return $this->hasMany(DispositivoControlAcceso::class);
    }

    public function eventosAcceso(): HasMany
    {
        return $this->hasMany(EventoAcceso::class);
    }

    public function miembros(): HasMany
    {
        return $this->hasMany(Miembro::class);
    }
}
