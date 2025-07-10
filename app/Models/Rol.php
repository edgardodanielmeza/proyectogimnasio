<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Rol extends SpatieRole
{
    // No es necesario definir $table si usas el nombre por defecto 'roles'.
    // No es necesario $fillable ni las relaciones permisos() o usuarios(), Spatie lo maneja.
}