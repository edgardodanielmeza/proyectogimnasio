<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\DashboardGeneral;
use App\Livewire\GestionMembresias;
use App\Livewire\RegistroAccesoManual;
use App\Livewire\GestionClases;
use App\Livewire\FacturacionPagos;
use App\Livewire\GestionTiposMembresia;
use App\Livewire\GestionSucursales; // Importar el nuevo componente

Route::get('/', function () {
    // return view('welcome');
    // For now, redirect to dashboard if logged in, or login page
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login'); // Assuming you have a login route
});

// Rutas que requieren autenticación
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', DashboardGeneral::class)->name('dashboard');
    Route::get('/membresias', GestionMembresias::class)->name('membresias');
    Route::get('/accesos/manual', RegistroAccesoManual::class)->name('accesos.manual');
    Route::get('/clases', GestionClases::class)->name('clases');
    Route::get('/pagos', FacturacionPagos::class)->name('pagos');
    Route::get('/tipos-membresia', GestionTiposMembresia::class)->name('tipos-membresia.index')->middleware(['role:Admin']);
    Route::get('/sucursales', GestionSucursales::class)->name('sucursales.index')->middleware(['role:Admin']);

    // Placeholder for other routes like profile, etc.
    // Jetstream/Fortify usually provides /user/profile
});

// Basic Fortify/Jetstream auth routes (login, register, etc.) are usually defined elsewhere
// or automatically if you installed Jetstream.
// If not using Jetstream, you'd need to define login/logout routes manually.
// Example for logout if not using Jetstream's default:
// Route::post('/logout', function () {
//     auth()->logout();
//     request()->session()->invalidate();
//     request()->session()->regenerateToken();
//     return redirect('/');
// })->name('logout');

// Fallback route for login if not defined by Jetstream/Fortify
if (!Route::has('login')) {
    Route::get('/login', function() {
        // This is a simplistic placeholder.
        // In a real app, you'd have a login view and controller/action.
        // If using Livewire for login, it would be a Livewire component route.
        if (class_exists(\Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class)) {
            // If Fortify is installed, its routes should handle this.
            // This is just a fallback to avoid errors if routes are not published.
             return 'Por favor, configure sus rutas de autenticación. Intente ejecutar `php artisan vendor:publish --tag=fortify-routes`.';
        }
        return 'Página de Login (Placeholder) - Por favor configure la autenticación.';
    })->name('login');
}
if (!Route::has('logout')) {
     Route::post('/logout-placeholder', function () { /* ... */ })->name('logout'); // Placeholder to avoid errors in layout if logout route not defined
}

// Ensure `npm run dev` is running to compile assets.
