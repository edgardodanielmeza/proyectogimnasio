<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CategoriaProducto;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'apellido' => 'Administrador',
            'email' => 'admin@gim.com',
            'password' => Hash::make('rootadmin123'),
        ]);
        CategoriaProducto::create([
            'nombre' => 'Suplementos',
            'descripcion' => 'Productos nutricionales y suplementos deportivos',
            'created_at' => now(),
            'updated_at' => now()
        ]);

         CategoriaProducto::create([
            'nombre' => 'Ropa',
            'descripcion' => 'Ropa deportiva y accesorios',
            'created_at' => now(),
            'updated_at' => now()
        ]);
         $sucursalCentral = Sucursal::create([
            'nombre' => 'Sucursal Central',
            'direccion' => 'Av. Principal 123, Ciudad',
            'telefono' => '555-1234',
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
