<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\EventoAcceso;
use App\Models\DispositivoControlAcceso;
use App\Models\Sucursal; // Import Sucursal
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RegistroAccesoManual extends Component
{
    public string $title = "Control de Acceso Manual";

    public $terminoBusqueda = '';
    public $miembroEncontrado;
    public $resultadoBusqueda = null;
    public $mensajeResultado = '';

    public $ultimosAccesosManuales = []; // Para la lista opcional

    public function mount()
    {
        // Opcional: Cargar últimos accesos manuales si se va a mostrar una lista
        // $this->cargarUltimosAccesos();
    }

    public function resetResultado()
    {
        $this->miembroEncontrado = null;
        $this->resultadoBusqueda = null;
        $this->mensajeResultado = '';
        $this->resetErrorBag();
    }

    public function updatedTerminoBusqueda()
    {
        // Si se desea búsqueda en tiempo real, resetear aquí.
        // $this->resetResultado();
    }

    public function buscarMiembro()
    {
        $this->validate(['terminoBusqueda' => 'required|min:3'], [
            'terminoBusqueda.required' => 'El término de búsqueda es obligatorio.',
            'terminoBusqueda.min' => 'El término de búsqueda debe tener al menos 3 caracteres.'
        ]);
        $this->resetResultado();

        $miembro = Miembro::with([
                            'membresiaActivaActual.tipoMembresia',
                            'ultimaMembresiaGeneral.tipoMembresia'
                        ])
                        ->where(function ($query) {
                            $query->where('nombre', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('apellido', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('email', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('codigo_acceso_numerico', $this->terminoBusqueda);
                        })
                        ->first();

        if ($miembro) {
            $this->miembroEncontrado = $miembro;
            $membresiaRelevante = $miembro->membresiaActivaActual ?? $miembro->ultimaMembresiaGeneral;

            if ($membresiaRelevante) {
                $fechaFin = Carbon::parse($membresiaRelevante->fecha_fin);
                $hoy = Carbon::today();

                if ($membresiaRelevante->estado == 'activa' && $fechaFin->gte($hoy)) {
                    $this->resultadoBusqueda = 'acceso_permitido';
                    $this->mensajeResultado = 'ACCESO PERMITIDO. Membresía: ' . ($membresiaRelevante->tipoMembresia->nombre ?? 'N/D') . ' (Vence: ' . $fechaFin->format('d/m/Y') . ')';
                } elseif ($membresiaRelevante->estado == 'suspendida') {
                    $this->resultadoBusqueda = 'acceso_denegado';
                    $this->mensajeResultado = 'ACCESO DENEGADO. Membresía SUSPENDIDA: ' . ($membresiaRelevante->tipoMembresia->nombre ?? 'N/D');
                } elseif ($membresiaRelevante->estado == 'cancelada') {
                    $this->resultadoBusqueda = 'acceso_denegado';
                    $this->mensajeResultado = 'ACCESO DENEGADO. Membresía CANCELADA: ' . ($membresiaRelevante->tipoMembresia->nombre ?? 'N/D');
                } else { // Vencida
                    $this->resultadoBusqueda = 'acceso_denegado';
                    $this->mensajeResultado = 'ACCESO DENEGADO. Membresía VENCIDA: ' . ($membresiaRelevante->tipoMembresia->nombre ?? 'N/D') . ' (Venció: ' . $fechaFin->format('d/m/Y') . ')';
                }
            } else {
                $this->resultadoBusqueda = 'sin_membresia_valida';
                $this->mensajeResultado = 'ACCESO DENEGADO. El miembro no tiene una membresía activa o válida.';
            }
        } else {
            $this->resultadoBusqueda = 'no_encontrado';
            $this->mensajeResultado = 'Miembro no encontrado con el término de búsqueda proporcionado.';
        }
    }

    public function registrarEntrada()
    {
        if ($this->miembroEncontrado && $this->resultadoBusqueda === 'acceso_permitido') {
            $user = Auth::user();
            $sucursalIdUsuario = $user->sucursal_id ?? null;

            // Si el usuario no tiene sucursal_id, intentar obtener la primera sucursal como fallback, o manejar el error.
            if (!$sucursalIdUsuario) {
                 $primeraSucursal = Sucursal::first();
                 if ($primeraSucursal) {
                     $sucursalIdUsuario = $primeraSucursal->id;
                 } else {
                     session()->flash('error_acceso', 'Error: No se pudo determinar la sucursal para el registro de acceso. Configure una sucursal para el usuario o asegúrese de que exista al menos una sucursal.');
                     return;
                 }
            }

            $dispositivoManual = DispositivoControlAcceso::firstOrCreate(
                ['identificador_dispositivo' => 'ACCESO_MANUAL_SISTEMA_' . $sucursalIdUsuario], // Hacerlo único por sucursal
                [
                    'nombre' => 'Acceso Manual (Sistema - Sucursal ' . $sucursalIdUsuario . ')',
                    'tipo' => 'manual_sistema',
                    'sucursal_id' => $sucursalIdUsuario,
                    'estado' => 'conectado'
                ]
            );

            EventoAcceso::create([
                'miembro_id' => $this->miembroEncontrado->id,
                'dispositivo_control_acceso_id' => $dispositivoManual->id,
                'sucursal_id' => $sucursalIdUsuario,
                'fecha_hora' => now(),
                'tipo_acceso_intentado' => 'manual_recepcion',
                'resultado' => 'permitido',
            ]);

            session()->flash('message_acceso', 'Entrada registrada para ' . $this->miembroEncontrado->nombre . ' ' . $this->miembroEncontrado->apellido);
            // $this->cargarUltimosAccesos(); // Opcional
            $this->terminoBusqueda = ''; // Limpiar el término de búsqueda
            $this->resetResultado();
        } else {
            session()->flash('error_acceso', 'No se puede registrar la entrada. Verifique el estado del miembro o realice una nueva búsqueda.');
        }
    }

    // public function cargarUltimosAccesos()
    // {
    //     // Lógica para cargar últimos N accesos manuales si se desea mostrar en la vista
    //     $this->ultimosAccesosManuales = EventoAcceso::whereHas('dispositivoControlAcceso', function($q){
    //         $q->where('tipo', 'manual_sistema');
    //     })->with('miembro')->latest()->take(5)->get();
    // }

    public function render()
    {
        return view('livewire.registro-acceso-manual')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
