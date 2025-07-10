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
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $recepcionistaRole = Role::firstOrCreate(['name' => 'Recepcionista', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Instructor', 'guard_name' => 'web']); // Se crea pero no se usa activamente aún

        $this->command->info('Roles Admin, Recepcionista e Instructor creados/verificados.');

        $allPermissions = Permission::pluck('id')->all();
        $adminRole->syncPermissions($allPermissions);
        $this->command->info('Todos los permisos asignados al rol Admin.');

        $recepcionistaPermissionsNames = [
            'ver lista miembros', 'crear miembro', 'editar miembro', 'ver miembro',
            'gestionar membresias miembro', 'ver lista tipos membresia',
            'ver lista pagos', 'registrar pago', 'registrar acceso manual', 'ver log accesos',
            'ver dashboard general',
        ];
        $recepcionistaPermObjects = Permission::whereIn('name', $recepcionistaPermissionsNames)->get();
        $recepcionistaRole->syncPermissions($recepcionistaPermObjects);
        $this->command->info(count($recepcionistaPermissionsNames) . ' permisos asignados al rol Recepcionista.');

        // Crear sucursal central si no existe (para asignarla a usuarios de ejemplo)
        $sucursalCentral = Sucursal::firstOrCreate(
            ['nombre' => 'Sucursal Central'],
            ['direccion' => 'Av. Principal 123, Ciudad', 'telefono' => '555-1234', 'horario_atencion' => 'L-V 08:00-22:00']
        );

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gim.com'],
            [
                'name' => 'Admin',
                'apellido' => 'Gimnasio',
                'password' => Hash::make('rootadmin123'),
                'sucursal_id' => $sucursalCentral->id,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole($adminRole);
        $this->command->info('Usuario admin@gim.com creado/actualizado y asignado rol Admin.');

        $recepcionistaUser = User::firstOrCreate(
            ['email' => 'recepcion@gim.com'],
            [
                'name' => 'Recepcionista',
                'apellido' => 'Gimnasio',
                'password' => Hash::make('password123'),
                'sucursal_id' => $sucursalCentral->id,
                'activo' => true,
                'email_verified_at' => now(),
            ]
        );
        $recepcionistaUser->assignRole($recepcionistaRole);
        $this->command->info('Usuario recepcion@gim.com creado/actualizado y asignado rol Recepcionista.');
    }
}
