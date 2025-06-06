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

    public function render()
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

        // 3. (Opcional) Total de Ingresos del Mes Actual
        $inicioMes = Carbon::now()->startOfMonth()->format('Y-m-d');
        $finMes = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->totalIngresosMesActual = Pago::whereBetween('fecha_pago', [$inicioMes, $finMes])
                                            ->sum('monto');

        return view('livewire.dashboard-general')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
