<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'ver lista miembros', 'crear miembro', 'editar miembro', 'eliminar miembro', 'ver miembro',
            'gestionar membresias miembro',
            'ver lista tipos membresia', 'crear tipo membresia', 'editar tipo membresia', 'eliminar tipo membresia',
            'ver lista sucursales', 'crear sucursal', 'editar sucursal', 'eliminar sucursal',
            'ver lista pagos', 'registrar pago', 'gestionar facturacion',
            'registrar acceso manual', 'ver log accesos', 'gestionar dispositivos acceso', 'ver panel monitoreo dispositivos',
            'ver lista usuarios', 'crear usuario', 'editar usuario', 'eliminar usuario', 'asignar roles',
            'ver lista roles', 'crear rol', 'editar rol', 'eliminar rol', 'asignar permisos a rol',
            'ver dashboard general',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        $this->command->info(count($permissions) . ' permisos base creados o verificados.');
    }
}
