<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoMembresia extends Model
{
    use HasFactory;

    protected $table = 'tipos_membresia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'duracion_dias',
        'precio',
        'acceso_multisucursal', // <-- AÑADIDO
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_dias' => 'integer', // Asegurar que duracion_dias también esté casteado si no lo estaba
        'acceso_multisucursal' => 'boolean', // <-- AÑADIDO
    ];

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class);
    }
}
