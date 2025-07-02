<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Definición de Permisos
        $permissions = [
            // Permisos para Miembros
            'ver lista miembros',
            'crear miembro',
            'editar miembro',
            'eliminar miembro',
            'ver miembro', // Detalle

            // Permisos para Membresías (de un miembro)
            'gestionar membresias miembro', // Añadir, renovar, cancelar membresía de un miembro específico

            // Permisos para Tipos de Membresía
            'ver lista tipos membresia',
            'crear tipo membresia',
            'editar tipo membresia',
            'eliminar tipo membresia',

            // Permisos para Sucursales
            'ver lista sucursales',
            'crear sucursal',
            'editar sucursal',
            'eliminar sucursal',

            // Permisos para Pagos y Facturación
            'ver lista pagos',
            'registrar pago',
            'gestionar facturacion', // Ver facturas, generar, etc.

            // Permisos para Control de Acceso
            'registrar acceso manual',
            'ver log accesos',
            'gestionar dispositivos acceso', // CRUD de dispositivos

            // Permisos para Usuarios del sistema
            'ver lista usuarios',
            'crear usuario',
            'editar usuario',
            'eliminar usuario',
            'asignar roles',

            // Permisos para Roles y Permisos (Gestión de RBAC)
            'ver lista roles',
            'crear rol',
            'editar rol',
            'eliminar rol',
            'asignar permisos a rol',

            // Permisos para Dashboard
            'ver dashboard general',
            'ver dashboard sucursal', // Si aplica

            // Permisos para Clases (si se implementa)
            // 'ver lista clases',
            // 'crear clase',
            // 'editar clase',
            // 'eliminar clase',
            // 'inscribir miembro a clase',

            // Permisos para Productos (si se implementa)
            // 'ver lista productos',
            // 'crear producto',
            // 'editar producto',
            // 'eliminar producto',
            // 'gestionar inventario',
            // 'registrar venta producto',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $this->command->info('Permisos base creados exitosamente.');
    }
}
