<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Spatie\Permission\Models\Permission as SpatiePermission; // Uncomment if using Spatie Laravel Permissions

// class Permiso extends SpatiePermission // Uncomment if using Spatie Laravel Permissions
class Permiso extends Model // Comment this line if using Spatie Laravel Permissions
{
    use HasFactory;

    protected $table = 'permisos'; // Ensure this matches your table name if not using Spatie

    protected $fillable = [
        'nombre',
        'descripcion',
        'guard_name', // Needed for Spatie
    ];

    // Comment out if using Spatie, as it handles this
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'rol_permiso');
    }
}
