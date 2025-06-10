<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'ver_dashboard',
            'ver_panel_monitoreo_dispositivos',
            'ver_informes_acceso', // Nuevo permiso
            'gestionar_usuarios',
            'gestionar_roles',
            'gestionar_permisos',
            'gestionar_miembros',
            'ver_historial_membresias_miembro',
            'añadir_membresia_miembro',
            'renovar_membresia_miembro',
            'cancelar_membresia_miembro',
            'gestionar_tipos_membresia',
            'gestionar_sucursales',
            'registrar_acceso_manual',
            'ver_eventos_acceso',
            'gestionar_dispositivos_acceso',
            'gestionar_reglas_acceso',
            'ver_informes_facturacion',
            'registrar_pagos',
            'gestionar_productos',
            'configurar_sistema',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $recepcionistaRole = Role::firstOrCreate(['name' => 'Recepcionista', 'guard_name' => 'web']);
        $instructorRole = Role::firstOrCreate(['name' => 'Instructor', 'guard_name' => 'web']);

        // Assign permissions to roles
        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());

        // Recepcionista permissions
        $recepcionistaPermissions = [
            'ver_dashboard',
            'ver_panel_monitoreo_dispositivos',
            // 'ver_informes_acceso', // Decidir si Recepcionista puede ver todos los informes de acceso
            'gestionar_miembros',
            'ver_historial_membresias_miembro',
            'añadir_membresia_miembro',
            'renovar_membresia_miembro',
            'cancelar_membresia_miembro',
            'registrar_acceso_manual',
            'ver_eventos_acceso',
            'registrar_pagos',
            'ver_informes_facturacion',
        ];
        $recepcionistaRole->givePermissionTo($recepcionistaPermissions);

        // Instructor permissions
        $instructorPermissions = [
            'ver_dashboard',
            'ver_eventos_acceso',
        ];
        $instructorRole->givePermissionTo($instructorPermissions);

        // At this point, users would normally be created and assigned roles.
        // For now, we'll focus on permissions for roles.

        // Assign 'Admin' role to the existing admin user if found
        $adminUser = \App\Models\User::where('email', 'admin@gim.com')->first();
        if ($adminUser) {
            $adminUser->assignRole($adminRole);
        }

        // Example of creating a new user and assigning a role:
        // $user = \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => bcrypt('password'),
        // ]);
        // $user->assignRole($recepcionistaRole);
    }
}
