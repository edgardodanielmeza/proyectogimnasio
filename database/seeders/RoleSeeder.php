<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Roles
        $adminRole = Role::findOrCreate('Admin', 'web');
        $recepcionistaRole = Role::findOrCreate('Recepcionista', 'web');
        $instructorRole = Role::findOrCreate('Instructor', 'web'); // Aunque no tenga permisos definidos aún

        $this->command->info('Roles Admin, Recepcionista e Instructor creados.');

        // Asignar todos los permisos al rol de Admin
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);
        $this->command->info('Todos los permisos asignados al rol Admin.');

        // Permisos para Recepcionista
        $recepcionistaPermissions = [
            'ver lista miembros',
            'crear miembro',
            'editar miembro',
            'ver miembro',
            'gestionar membresias miembro',
            'ver lista tipos membresia', // Solo ver, no CRUD completo
            'ver lista pagos',
            'registrar pago',
            'registrar acceso manual',
            'ver log accesos', // Ver log general o de su sucursal
            'ver dashboard general', // O un dashboard específico para recepción
            // Podría tener permisos para ver productos y registrar ventas si aplica
        ];
        foreach ($recepcionistaPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $recepcionistaRole->givePermissionTo($permission);
            } else {
                $this->command->warn("Permiso '{$permissionName}' no encontrado, no se asignó a Recepcionista.");
            }
        }
        $this->command->info('Permisos asignados al rol Recepcionista.');

        // Permisos para Instructor (ejemplo básico, expandir según necesidad)
        $instructorPermissions = [
            'ver lista miembros', // Para ver quiénes son sus alumnos, etc.
            'ver miembro',
            // 'ver lista clases',
            // 'inscribir miembro a clase',
            // 'tomar asistencia clase',
        ];
        // foreach ($instructorPermissions as $permissionName) {
        //     $permission = Permission::where('name', $permissionName)->first();
        //     if ($permission) {
        //         $instructorRole->givePermissionTo($permission);
        //     } else {
        //         $this->command->warn("Permiso '{$permissionName}' no encontrado, no se asignó a Instructor.");
        //     }
        // }
        // $this->command->info('Permisos asignados al rol Instructor.');


        // Asignar rol Admin al usuario admin@gim.com
        $adminUser = User::where('email', 'admin@gim.com')->first();
        if ($adminUser) {
            if (!$adminUser->hasRole('Admin')) {
                $adminUser->assignRole('Admin');
                $this->command->info('Rol Admin asignado al usuario admin@gim.com.');
            } else {
                $this->command->info('Usuario admin@gim.com ya tiene el rol Admin.');
            }
        } else {
            $this->command->warn('Usuario admin@gim.com no encontrado, no se pudo asignar rol Admin.');
            // Considerar crear el usuario admin aquí si no existe, aunque el DatabaseSeeder principal ya lo hace.
            // Este seeder de roles debería ejecutarse DESPUÉS de que el usuario admin exista.
        }

        // Crear un usuario de ejemplo para Recepcionista si no existe
        $recepcionistaUser = User::where('email', 'recepcion@gim.com')->first();
        if (!$recepcionistaUser) {
            $sucursalCentral = \App\Models\Sucursal::where('nombre', 'Sucursal Central')->first();
            $recepcionistaUser = User::create([
                'name' => 'Recepcionista',
                'apellido' => 'Gimnasio',
                'email' => 'recepcion@gim.com',
                'password' => Hash::make('password123'),
                'sucursal_id' => $sucursalCentral ? $sucursalCentral->id : null, // Asignar a sucursal central si existe
                'activo' => true,
            ]);
            $this->command->info('Usuario recepcion@gim.com creado.');
        }
        if ($recepcionistaUser) {
             if (!$recepcionistaUser->hasRole('Recepcionista')) {
                $recepcionistaUser->assignRole('Recepcionista');
                $this->command->info('Rol Recepcionista asignado al usuario recepcion@gim.com.');
            } else {
                $this->command->info('Usuario recepcion@gim.com ya tiene el rol Recepcionista.');
            }
        }


        // Crear un usuario de ejemplo para Instructor si no existe
        // $instructorUser = User::where('email', 'instructor@gim.com')->first();
        // if (!$instructorUser) {
        //     $sucursalCentral = \App\Models\Sucursal::where('nombre', 'Sucursal Central')->first();
        //     $instructorUser = User::create([
        //         'name' => 'Instructor',
        //         'apellido' => 'Fitness',
        //         'email' => 'instructor@gim.com',
        //         'password' => Hash::make('password123'),
        //         'sucursal_id' => $sucursalCentral ? $sucursalCentral->id : null,
        //         'activo' => true,
        //     ]);
        //     $this->command->info('Usuario instructor@gim.com creado.');
        // }
        // if ($instructorUser) {
        //      if (!$instructorUser->hasRole('Instructor')) {
        //         $instructorUser->assignRole('Instructor');
        //         $this->command->info('Rol Instructor asignado al usuario instructor@gim.com.');
        //     } else {
        //         $this->command->info('Usuario instructor@gim.com ya tiene el rol Instructor.');
        //     }
        // }
    }
}
