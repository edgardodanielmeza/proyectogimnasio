<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Spatie\Permission\Models\Role as SpatieRole; // Uncomment if using Spatie Laravel Permissions

// class Rol extends SpatieRole // Uncomment if using Spatie Laravel Permissions
class Rol extends Model // Comment this line if using Spatie Laravel Permissions
{
    use HasFactory;

    protected $table = 'roles'; // Ensure this matches your table name if not using Spatie

    protected $fillable = [
        'nombre',
        'descripcion',
        'guard_name', // Needed for Spatie
    ];

    // Comment out if using Spatie, as it handles this
    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso');
    }

    // Comment out if using Spatie, as it handles this
    public function usuarios(): BelongsToMany
    {
        // Assumes you have a User model at App\Models\User
        return $this->belongsToMany(User::class, 'usuario_rol');
    }
}
