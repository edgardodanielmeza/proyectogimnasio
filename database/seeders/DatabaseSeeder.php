<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash; // Necesario si User::factory() lo usa o si se crea User aquí
use App\Models\User; // Necesario si se crea User aquí directamente
use App\Models\Sucursal;
use App\Models\CategoriaProducto;
use App\Models\TipoMembresia;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Limpiar caché de permisos de Spatie antes de sembrar
        // Es importante ejecutar esto ANTES de que RoleSeeder intente asignar permisos
        Artisan::call('permission:cache-reset');
        $this->command->info('Caché de permisos reseteada.');

        // Crear Sucursales primero, ya que User y otros pueden depender de ellas
        $sucursalCentral = Sucursal::firstOrCreate(
            ['nombre' => 'Sucursal Central'],
            [
                'direccion' => 'Av. Principal 123, Ciudad',
                'telefono' => '555-1234',
                'email' => 'central@gimnasio.com', // Email opcional para la sucursal
                'horario_atencion' => 'L-V 06:00-22:00, S 08:00-20:00', // Horario opcional
            ]
        );
        $this->command->info('Sucursal Central creada/verificada.');

        Sucursal::firstOrCreate(
            ['nombre' => 'Sucursal Norte'],
            [
                'direccion' => 'Calle Falsa 456, Sector Norte',
                'telefono' => '555-5678',
                'email' => 'norte@gimnasio.com',
                'horario_atencion' => 'L-V 07:00-21:00, S 09:00-18:00',
            ]
        );
        $this->command->info('Sucursal Norte creada/verificada.');

        // El seeder 'RoleSeeder' ahora se encarga de crear los usuarios admin y recepcionista.
        // Si se desea crear un usuario genérico aquí, se puede hacer:
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'sucursal_id' => $sucursalCentral->id, // Ejemplo de asignación
        // ]);

        // Crear Categorías de Producto
        CategoriaProducto::firstOrCreate(
            ['nombre' => 'Suplementos'],
            ['descripcion' => 'Productos nutricionales y suplementos deportivos']
        );
        CategoriaProducto::firstOrCreate(
            ['nombre' => 'Ropa'],
            ['descripcion' => 'Ropa deportiva y accesorios']
        );
        CategoriaProducto::firstOrCreate(
            ['nombre' => 'Bebidas'],
            ['descripcion' => 'Bebidas energéticas e hidratantes']
        );
        $this->command->info('Categorías de producto creadas/verificadas.');

        // Crear Tipos de Membresía
        TipoMembresia::firstOrCreate(
            ['nombre' => 'Mensual'],
            [
                'descripcion' => 'Acceso completo por 30 días. Área de pesas y cardio.',
                'duracion_dias' => 30,
                'precio' => 130000.00,
                'acceso_multisucursal' => false, // Por defecto no multisucursal
            ]
        );
        TipoMembresia::firstOrCreate(
            ['nombre' => 'Trimestral'],
            [
                'descripcion' => 'Acceso completo por 90 días. Descuento aplicado.',
                'duracion_dias' => 90,
                'precio' => 350000.00,
                'acceso_multisucursal' => false,
            ]
        );
        TipoMembresia::firstOrCreate(
            ['nombre' => 'Anual VIP'],
            [
                'descripcion' => 'Acceso completo por 365 días. Acceso a todas las sucursales y clases especiales.',
                'duracion_dias' => 365,
                'precio' => 1200000.00,
                'acceso_multisucursal' => true,
            ]
        );
        $this->command->info('Tipos de membresía creados/verificados.');

        // Llamar a los seeders de Permisos y Roles en el orden correcto
        $this->call([
            PermissionSeeder::class, // Primero los permisos
            RoleSeeder::class,       // Luego los roles y asignaciones
        ]);

        // Aquí podrías llamar a otros seeders específicos de módulos si los tienes
        // Ejemplo: $this->call(MiembroSeeder::class);
        // Ejemplo: $this->call(ProductoSeeder::class);

        $this->command->info('Base de datos inicializada con datos de prueba, roles y permisos.');
    }
}
