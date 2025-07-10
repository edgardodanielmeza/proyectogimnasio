<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

use App\Models\Miembro;
use App\Models\Membresia;
use App\Models\Pago;
use Carbon\Carbon;

class DashboardGeneral extends Component
{
    public string $title = "Panel de Control Principal";

    public $totalMiembrosActivos = 0;
    public $totalMembresiasPorVencer = 0;
    public $totalIngresosMesActual = 0; // Opcional
    public $nuevosMiembrosHoy = 0;
    public $ingresosHoy = 0;
    public $ultimosPagosHoy = []; // Para mostrar una lista de los últimos pagos
    public $listaNuevosMiembrosHoy = []; // Para mostrar una lista de nuevos miembros


//     public function render()
//     {
//         // 1. Total de Miembros Activos
//         $this->totalMiembrosActivos = Miembro::whereHas('membresias', function ($query) {
//             $query->where('estado', 'activa')
//                   ->where('fecha_fin', '>=', Carbon::today()->format('Y-m-d'));
//         })->count();

//         // 2. Total de Membresías Próximas a Vencer (ej. en los próximos 7 días)
//         $fechaHoy = Carbon::today();
//         $fechaLimiteVencimiento = Carbon::today()->addDays(7);
//         $this->totalMembresiasPorVencer = Membresia::where('estado', 'activa')
//             ->where('fecha_fin', '>=', $fechaHoy->format('Y-m-d'))
//             ->where('fecha_fin', '<=', $fechaLimiteVencimiento->format('Y-m-d'))
//             ->count();

//         // 3. (Opcional) Total de Ingresos del Mes Actual
//         $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
//         $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');
//         $this->totalIngresosMesActual = Pago::whereBetween('fecha_pago', [$inicioMes, $finMes])
//                                             ->sum('monto');
//          // --- NUEVOS DATOS ---
//         // 4. Nuevos Miembros Registrados Hoy
//         $this->nuevosMiembrosHoy = Miembro::whereDate('created_at', Carbon::today())->count();
//         $this->listaNuevosMiembrosHoy = Miembro::whereDate('created_at', Carbon::today())
//                                             ->orderBy('created_at', 'desc')
//                                             ->take(5) // Tomar los últimos 5 por ejemplo
//                                             ->get();

//         // 5. Total de Ingresos (Pagos) Registrados Hoy
//         $this->ingresosHoy = Pago::whereDate('fecha_pago', Carbon::today())->sum('monto');
//         $this->ultimosPagosHoy = Pago::whereDate('fecha_pago', Carbon::today())
//                                     ->with('miembro') // Cargar relación con miembro
//                                     ->orderBy('created_at', 'desc')
//                                     ->take(5) // Tomar los últimos 5 pagos
//                                     ->get();

//         return view('livewire.dashboard-general')
//             ->layout('layouts.app', ['title' => $this->title]);
//     }
// }
 public function mount()
    {
        $this->cargarDatosDashboard();
    }

    public function cargarDatosDashboard()
    {
        // 1. Total de Miembros Activos
        $this->totalMiembrosActivos = Miembro::whereHas('membresias', function ($query) {
            $query->where('estado', 'activa')
                  ->where('fecha_fin', '>=', Carbon::today()->format('Y-m-d'));
        })->count();

        // 2. Total de Membresías Próximas a Vencer (ej. en los próximos 7 días)
        $fechaHoy = Carbon::today();
        $fechaLimiteVencimiento = Carbon::today()->addDays(7);
        $this->totalMembresiasPorVencer = Membresia::where('estado', 'activa')
            ->where('fecha_fin', '>=', $fechaHoy->format('Y-m-d'))
            ->where('fecha_fin', '<=', $fechaLimiteVencimiento->format('Y-m-d'))
            ->count();

        // 3. Total de Ingresos del Mes Actual
        $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
        $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->totalIngresosMesActual = Pago::whereBetween('fecha_pago', [$inicioMes, $finMes])
                                            ->sum('monto');

        // --- NUEVOS DATOS ---
        // 4. Nuevos Miembros Registrados Hoy
        $this->nuevosMiembrosHoy = Miembro::whereDate('created_at', Carbon::today())->count();
        $this->listaNuevosMiembrosHoy = Miembro::whereDate('created_at', Carbon::today())
                                            ->orderBy('created_at', 'desc')
                                            ->take(5) // Tomar los últimos 5 por ejemplo
                                            ->get();

        // 5. Total de Ingresos (Pagos) Registrados Hoy
        $this->ingresosHoy = Pago::whereDate('fecha_pago', Carbon::today())->sum('monto');
        $this->ultimosPagosHoy = Pago::whereDate('fecha_pago', Carbon::today())
                                    ->with('miembro') // Cargar relación con miembro
                                    ->orderBy('created_at', 'desc')
                                    ->take(5) // Tomar los últimos 5 pagos
                                    ->get();
    }

    public function render()
    {
        // Opcional: Si quieres que los datos se refresquen automáticamente cada X segundos
        // $this->dispatch('datosActualizados'); // Necesitarías un listener en JS o Livewire polling
        // O simplemente se cargan en mount y si el usuario refresca la página.
        // Para un dashboard en tiempo real, se usaría polling o websockets.

        return view('livewire.dashboard-general', [
            // Los datos ya están como propiedades públicas
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
