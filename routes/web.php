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
    Route::get('/sucursales', GestionSucursales::class)->name('sucursales.index'); // Nueva ruta
    Route::get('/dashboard', DashboardGeneral::class)->name('dashboard');
    //Route::get('/membresias', GestionMembresias::class)->name('membresias');
    Route::get('/pagos', FacturacionPagos::class)->name('pagos');
    Route::get('/accesomanual', RegistroAccesoManual::class)->name('accesomanual');
    //Route::get('/tipos-membresia', GestionTiposMembresia::class)->name('tipos-membresia.index'); // Nueva ruta
    Route::get('/membresias', GestionMembresias::class)->name('membresias');
    Route::get('/tipos-membresia', GestionTiposMembresia::class)->name('tipos-membresia.index');
	
 });


require __DIR__.'/auth.php';
