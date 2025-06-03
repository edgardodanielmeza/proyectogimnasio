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
        'logo_path',
    ];

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
