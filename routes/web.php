<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController; // Aunque no se use directamente aquí, es parte de auth.
use App\Livewire\DashboardGeneral;
use App\Livewire\GestionMembresias;
use App\Livewire\FacturacionPagos;
use App\Livewire\RegistroAccesoManual;
use App\Livewire\GestionTiposMembresia;
use App\Livewire\GestionSucursales;
use App\Livewire\GestionRoles;
use App\Livewire\GestionUsuarios;
use App\Livewire\GestionDispositivos;


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
     ->name('logout')
     ->middleware('auth'); // Logout solo para autenticados


// Ruta de fallback para '/' si el usuario está autenticado, redirige a dashboard.
// Si no, Laravel Breeze maneja la redirección a login.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login'); // O la vista de bienvenida que tengas
})->name('home.redirect');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardGeneral::class)
        ->name('dashboard')
        ->middleware('can:ver dashboard general');

    Route::get('/sucursales', GestionSucursales::class)
        ->name('sucursales.index')
        ->middleware('can:ver lista sucursales');

    Route::get('/membresias', GestionMembresias::class)
        ->name('membresias')
        ->middleware('can:ver lista miembros');

    Route::get('/tipos-membresia', GestionTiposMembresia::class)
        ->name('tipos-membresia.index')
        ->middleware('can:ver lista tipos membresia');

    Route::get('/pagos', FacturacionPagos::class)
        ->name('pagos')
        ->middleware('can:ver lista pagos');

    Route::get('/accesos/manual', RegistroAccesoManual::class)
        ->name('accesos.manual')
        ->middleware('can:registrar acceso manual');

    // Rutas de Administración del Sistema
    Route::get('/admin/roles', GestionRoles::class) // Prefijo 'admin/' para claridad
        ->name('admin.roles.index')
        ->middleware('can:ver lista roles');

    Route::get('/admin/usuarios', GestionUsuarios::class)
        ->name('admin.usuarios.index')
        ->middleware('can:ver lista usuarios');

    Route::get('/admin/dispositivos', GestionDispositivos::class)
        ->name('admin.dispositivos.index')
        ->middleware('can:gestionar dispositivos acceso');

});


require __DIR__.'/auth.php';
