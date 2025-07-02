<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CategoriaProducto;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan; // Para limpiar caché de permisos

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie antes de sembrar
        Artisan::call('permission:cache-reset');

        // User::factory(10)->create();

        // Crear Sucursales primero, ya que User puede depender de ellas
        $sucursalCentral = Sucursal::firstOrCreate(
            ['nombre' => 'Sucursal Central'],
            [
                'direccion' => 'Av. Principal 123, Ciudad',
                'telefono' => '555-1234',
            ]
        );
        Sucursal::firstOrCreate(
            ['nombre' => 'Todas las Sucursale habilitadas '], // Nombre largo, considerar acortar o cambiar
            [
                'direccion' => 'cualquiera',
                'telefono' => '555-1234', // Mismo teléfono que central, podría ser un placeholder
            ]
        );

        // Crear Usuario Admin (sin rol aún, RoleSeeder lo asignará)
        User::firstOrCreate(
            ['email' => 'admin@gim.com'],
            [
                'name' => 'Admin User',
                'apellido' => 'Administrador',
                'password' => Hash::make('rootadmin123'),
                'sucursal_id' => $sucursalCentral->id, // Asignar a Sucursal Central por defecto
                'activo' => true,
            ]
        );

        // Crear Categorías de Producto
        CategoriaProducto::firstOrCreate(
            ['nombre' => 'Suplementos'],
            ['descripcion' => 'Productos nutricionales y suplementos deportivos']
        );

        CategoriaProducto::firstOrCreate(
            ['nombre' => 'Ropa'],
            ['descripcion' => 'Ropa deportiva y accesorios']
        );

        // Crear Tipos de Membresía
        TipoMembresia::firstOrCreate(
            ['nombre' => 'Mensual'],
            [
                'descripcion' => 'Acceso a área de pesas y cardio',
                'duracion_dias' => 30,
                'precio' => 130000.00, // Asegurar formato numérico correcto
            ]
        );

        // Llamar a los seeders de Permisos y Roles
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
        ]);

        // Aquí podrías llamar a otros seeders específicos de módulos si los tienes
        // Ejemplo: $this->call(MiembroSeeder::class);
        // Ejemplo: $this->call(ProductoSeeder::class);

        $this->command->info('Base de datos inicializada con datos de prueba y roles/permisos.');
    }
}
            'created_at' => now(),
            'updated_at' => now()
        ]);

         CategoriaProducto::create([
            'nombre' => 'Ropa',
            'descripcion' => 'Ropa deportiva y accesorios',
            'created_at' => now(),
            'updated_at' => now()
        ]);

         $Mensual = TipoMembresia::create([
            'nombre' => 'Mensual',
            'descripcion' => 'Acceso a área de pesas y cardio',
            'duracion_dias' => 30,
            'precio' => 130.000,
            'created_at' => now(),
            'updated_at' => now()
        ]);


    }
}
