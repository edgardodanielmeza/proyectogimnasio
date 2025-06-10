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
})->middleware(['auth', 'verified', 'can:ver_dashboard'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', DashboardGeneral::class)->name('dashboard')->middleware('can:ver_dashboard');
    Route::get('/sucursales', GestionSucursales::class)->name('sucursales.index')->middleware('can:gestionar_sucursales');
    Route::get('/pagos', FacturacionPagos::class)->name('pagos')->middleware('can:ver_informes_facturacion'); // O añadir 'can:registrar_pagos' si es necesario
    Route::get('/accesos/manual', RegistroAccesoManual::class)->name('accesos.manual')->middleware('can:registrar_acceso_manual');
    Route::get('/membresias', GestionMembresias::class)->name('membresias')->middleware('can:gestionar_miembros');
    Route::get('/tipos-membresia', GestionTiposMembresia::class)->name('tipos-membresia.index')->middleware('can:gestionar_tipos_membresia');

    // Rutas que antes eran solo para Admin, ahora con permisos específicos
    Route::get('/roles', \App\Livewire\GestionRoles::class)->name('gestion.roles')->middleware('can:gestionar_roles');
    Route::get('/usuarios/asignar-roles', \App\Livewire\AsignarRolesUsuario::class)->name('usuarios.asignar-roles')->middleware('can:gestionar_usuarios');
    Route::get('/dispositivos-acceso', \App\Livewire\GestionDispositivos::class)->name('dispositivos.index')->middleware(['auth', 'can:gestionar_dispositivos_acceso']);
    Route::get('/panel-monitoreo-dispositivos', \App\Livewire\PanelMonitoreoDispositivos::class)->name('panel.monitoreo.dispositivos')->middleware(['auth', 'can:ver_panel_monitoreo_dispositivos']);
    Route::get('/informes/accesos', \App\Livewire\InformeEventosAcceso::class)->name('informes.accesos')->middleware(['auth', 'can:ver_informes_acceso']);

    // Ejemplo de cómo podría quedar un grupo específico de Admin si aún fuera necesario para otras rutas no cubiertas por permisos granulares
    // Route::middleware(['role:Admin'])->group(function () {
        // Otras rutas solo para Admin
    // });
 });


require __DIR__.'/auth.php';
