<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Miembro extends Model
{
    use HasFactory;

    protected $table = 'miembros';

    protected $fillable = [
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'email',
        'fecha_nacimiento',
        'foto_path',
        'codigo_acceso_numerico',
        'plantilla_huella',
        'sucursal_id',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        // Consider hashing codigo_acceso_numerico if it's sensitive
        // 'codigo_acceso_numerico' => 'hashed',
    ];

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class)->orderBy('fecha_fin', 'desc');
    }

    public function latestMembresia(): HasOne
    {
        return $this->hasOne(Membresia::class)->orderBy('fecha_fin', 'desc');
    }

    // Example of a more specific "current active" membership
    public function activeMembresia(): HasOne
    {
        return $this->hasOne(Membresia::class)
                    ->where('estado', 'activa')
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now())
                    ->orderBy('fecha_fin', 'desc');
    }


    public function eventosAcceso(): HasMany
    {
        return $this->hasMany(EventoAcceso::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }
}
