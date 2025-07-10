<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\DashboardGeneral;
use App\Livewire\GestionMembresias;
use App\Livewire\FacturacionPagos;
use App\Livewire\RegistroAccesoManual;
use App\Livewire\GestionTiposMembresia;
use App\Livewire\GestionSucursales;


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
     ->name('logout');


Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardGeneral::class)->name('dashboard')->middleware('can:ver dashboard general');

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

    // Rutas de Administración de Roles y Permisos
    Route::get('/gestion-roles', \App\Livewire\GestionRoles::class)
        ->name('roles.index')
        ->middleware('can:ver lista roles');

    Route::get('/gestion-usuarios', \App\Livewire\GestionUsuarios::class)
        ->name('usuarios.index')
        ->middleware('can:ver lista usuarios');

    Route::get('/gestion-dispositivos', \App\Livewire\GestionDispositivos::class)
        ->name('dispositivos.index')
        ->middleware('can:gestionar dispositivos acceso');

 });


require __DIR__.'/auth.php';
