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
        'acceso_multisucursal', // Añadido
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'duracion_dias' => 'integer',
        'acceso_multisucursal' => 'boolean', // Añadido
    ];

    public function membresias(): HasMany
    {
        return $this->hasMany(Membresia::class);
    }
}
