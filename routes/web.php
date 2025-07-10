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
use App\Livewire\GestionRoles;
use App\Livewire\GestionUsuarios;
use App\Livewire\GestionDispositivos;
// Asumo que tienes estos componentes, si no, deberás crearlos o ajustar las rutas:
use App\Livewire\PanelMonitoreoDispositivos; // Asumiendo que existe
use App\Livewire\InformeEventosAcceso;    // Asumiendo que existe

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');

Route::get('/', function () {
    if (auth()->check()) {
        // Si está autenticado, verificar si puede ver el dashboard
        if (auth()->user()->can('ver dashboard general')) {
            return redirect()->route('dashboard');
        }
        // Si no puede ver el dashboard general, quizás redirigir a perfil o una página por defecto
        // o simplemente mostrar una vista básica.
        // Por ahora, si no puede ver dashboard, y está logueado, va a profile.
        // O podrías tener una ruta 'home' sin permisos específicos después del login.
        return redirect()->route('profile.edit');
    }
    return view('auth.login');
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
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/roles', GestionRoles::class)
            ->name('roles.index')
            ->middleware('can:ver lista roles');

        Route::get('/usuarios', GestionUsuarios::class)
            ->name('usuarios.index')
            ->middleware('can:ver lista usuarios');

        Route::get('/dispositivos', GestionDispositivos::class)
            ->name('dispositivos.index')
            ->middleware('can:gestionar dispositivos acceso');

        // Manteniendo tus nuevas rutas pero con nuestros permisos (ajusta si es necesario)
        Route::get('/panel-monitoreo-dispositivos', PanelMonitoreoDispositivos::class)
            ->name('panel.monitoreo.dispositivos')
            ->middleware('can:ver panel monitoreo dispositivos');

        Route::get('/informes/accesos', InformeEventosAcceso::class)
            ->name('informes.accesos')
            ->middleware('can:ver log accesos'); // 'ver log accesos' es el que definimos
    });
});

require __DIR__.'/auth.php';
