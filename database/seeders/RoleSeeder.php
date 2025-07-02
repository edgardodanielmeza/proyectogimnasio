<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $recepcionistaRole = Role::firstOrCreate(['name' => 'Recepcionista', 'guard_name' => 'web']);
        $instructorRole = Role::firstOrCreate(['name' => 'Instructor', 'guard_name' => 'web']);

        $this->command->info('Roles Admin, Recepcionista e Instructor creados/verificados.');

        // Asignar todos los permisos al rol de Admin
        $allPermissions = Permission::pluck('id', 'id')->all(); // Obtener todos los IDs de permisos
        $adminRole->syncPermissions($allPermissions);
        $this->command->info('Todos los permisos asignados al rol Admin.');

        // Permisos para Recepcionista
        $recepcionistaPermissions = [
            'ver lista miembros',
            'crear miembro',
            'editar miembro',
            'ver miembro',
            'gestionar membresias miembro',
            'ver lista tipos membresia',
            'ver lista pagos',
            'registrar pago',
            'registrar acceso manual',
            'ver log accesos',
            'ver dashboard general',
        ];
        $recepcionistaPermObjects = Permission::whereIn('name', $recepcionistaPermissions)->get();
        $recepcionistaRole->syncPermissions($recepcionistaPermObjects);
        $this->command->info(count($recepcionistaPermissions) . ' permisos asignados al rol Recepcionista.');

        // Permisos para Instructor (ejemplo básico)
        $instructorPermissions = [
            'ver lista miembros',
            'ver miembro',
            'ver dashboard general', // Quizás un dashboard limitado
        ];
        $instructorPermObjects = Permission::whereIn('name', $instructorPermissions)->get();
        $instructorRole->syncPermissions($instructorPermObjects);
        $this->command->info(count($instructorPermissions) . ' permisos asignados al rol Instructor.');


        // --- Crear/Asignar Usuarios ---
        $sucursalCentral = Sucursal::where('nombre', 'Sucursal Central')->first();
        if (!$sucursalCentral) {
            // Crear sucursal central si no existe (DatabaseSeeder también lo hace, pero como fallback)
            $sucursalCentral = Sucursal::firstOrCreate(
                ['nombre' => 'Sucursal Central'],
                ['direccion' => 'Av. Principal 123, Ciudad', 'telefono' => '555-1234']
            );
            $this->command->info('Sucursal Central creada/verificada por RoleSeeder.');
        }

        // Usuario Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gim.com'],
            [
                'name' => 'Admin',
                'apellido' => 'Gimnasio',
                'password' => Hash::make('rootadmin123'), // Cambiar en producción
                'sucursal_id' => $sucursalCentral->id,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );
        if ($adminUser->wasRecentlyCreated || !$adminUser->hasRole('Admin')) {
            $adminUser->assignRole('Admin');
            $this->command->info('Usuario admin@gim.com creado/actualizado y asignado rol Admin.');
        }


        // Usuario Recepcionista
        $recepcionistaUser = User::firstOrCreate(
            ['email' => 'recepcion@gim.com'],
            [
                'name' => 'Recepcionista',
                'apellido' => 'Gimnasio',
                'password' => Hash::make('password123'), // Cambiar en producción
                'sucursal_id' => $sucursalCentral->id,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );
        if ($recepcionistaUser->wasRecentlyCreated || !$recepcionistaUser->hasRole('Recepcionista')) {
            $recepcionistaUser->assignRole('Recepcionista');
            $this->command->info('Usuario recepcion@gim.com creado/actualizado y asignado rol Recepcionista.');
        }

        // Usuario Instructor (Opcional)
        // $instructorUser = User::firstOrCreate(
        //     ['email' => 'instructor@gim.com'],
        //     [
        //         'name' => 'Instructor',
        //         'apellido' => 'Fitness',
        //         'password' => Hash::make('password123'), // Cambiar en producción
        //         'sucursal_id' => $sucursalCentral->id,
        //         'activo' => true,
        //         'email_verified_at' => now(),
        //     ]
        // );
        // if ($instructorUser->wasRecentlyCreated || !$instructorUser->hasRole('Instructor')) {
        //     $instructorUser->assignRole('Instructor');
        //     $this->command->info('Usuario instructor@gim.com creado/actualizado y asignado rol Instructor.');
        // }
    }
}
