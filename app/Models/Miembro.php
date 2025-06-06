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

    public function latestMembresia(): HasOne // Generalmente la que termina más tarde o se creó más tarde
    {
        return $this->hasOne(Membresia::class)->orderBy('fecha_fin', 'desc')->orderBy('created_at', 'desc');
    }

    public function membresiaActivaActual(): HasOne
    {
        return $this->hasOne(Membresia::class)
                    ->where('estado', 'activa')
                    ->where('fecha_inicio', '<=', now()->format('Y-m-d'))
                    ->where('fecha_fin', '>=', now()->format('Y-m-d'))
                    ->orderBy('fecha_fin', 'desc'); // En caso de solapamientos (raro), la que termina más tarde
    }

    // Para obtener la última membresía registrada, sin importar su estado, útil si no hay ninguna activa
    public function ultimaMembresiaGeneral(): HasOne
    {
        return $this->hasOne(Membresia::class)
                        ->orderBy('fecha_inicio', 'desc') // La que comenzó más recientemente
                        ->orderBy('id', 'desc'); // O por created_at para la más nueva registrada
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
