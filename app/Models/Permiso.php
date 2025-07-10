<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permiso extends SpatiePermission
{
    // No es necesario definir $table si usas el nombre por defecto 'permissions'.
    // No es necesario $fillable ni la relación roles() aquí, Spatie lo maneja.
}