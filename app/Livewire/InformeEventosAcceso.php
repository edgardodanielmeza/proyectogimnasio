<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EventoAcceso;
use App\Models\Miembro;
use App\Models\Sucursal;
use Livewire\WithPagination;

class InformeEventosAcceso extends Component
{
    use WithPagination;

    public $eventosAcceso; // No es necesario inicializar como array si se usa paginate
    public $miembros;
    public $sucursales;

    public $filtroMiembroId = '';
    public $filtroSucursalId = '';
    public $filtroFechaDesde = '';
    public $filtroFechaHasta = '';
    public $filtroResultado = ''; // 'permitido', 'denegado', o '' para todos

    public $resultadosDisponibles = [
        'permitido' => 'Permitido',
        'denegado' => 'Denegado',
    ];

    // Estos se poblarán en mount() o se definirán estáticamente si son fijos
    public $tiposEventoDisponibles = [];
    public $metodosAccesoDisponibles = [];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->miembros = Miembro::orderBy('apellido')->orderBy('nombre')->get();
        $this->sucursales = Sucursal::orderBy('nombre')->get();

        // Estos valores deben coincidir con los enums de la migración EventoAcceso
        $this->tiposEventoDisponibles = [
            'entrada_permitida' => 'Entrada Permitida',
            'salida_permitida' => 'Salida Permitida',
            'intento_denegado_membresia' => 'Denegado (Membresía)',
            'intento_denegado_codigo' => 'Denegado (Código/QR)',
            'intento_denegado_desconocido' => 'Denegado (Desconocido)',
            'intento_denegado_horario' => 'Denegado (Horario)',
            'intento_denegado_otro' => 'Denegado (Otro)',
            'entrada_manual_recepcion' => 'Entrada Manual (Recepción)',
        ];
        $this->metodosAccesoDisponibles = [
            'codigo_numerico' => 'Código Numérico',
            'huella_digital' => 'Huella Digital',
            'facial' => 'Reconocimiento Facial',
            'qr_temporal' => 'QR Temporal',
            'manual_recepcion' => 'Manual Recepción',
            'desconocido' => 'Desconocido',
        ];

        // No llamar a aplicarFiltros() aquí para evitar carga inicial pesada sin filtros.
        // $this->aplicarFiltros();
        // En su lugar, inicializar $eventosAcceso como una colección paginada vacía o null
        $this->eventosAcceso = EventoAcceso::whereRaw('0=1')->paginate(15); // Paginate vacío
    }

    public function aplicarFiltros()
    {
        $query = EventoAcceso::query()
            ->with(['miembro', 'sucursal', 'dispositivoControlAcceso']); // Corregido el nombre de la relación

        if ($this->filtroMiembroId) {
            $query->where('miembro_id', $this->filtroMiembroId);
        }
        if ($this->filtroSucursalId) {
            $query->where('sucursal_id', $this->filtroSucursalId);
        }
        if ($this->filtroFechaDesde) {
            $query->whereDate('fecha_hora', '>=', $this->filtroFechaDesde);
        }
        if ($this->filtroFechaHasta) {
            $query->whereDate('fecha_hora', '<=', $this->filtroFechaHasta);
        }
        if ($this->filtroResultado) {
            $query->where('resultado', $this->filtroResultado);
        }

        $this->eventosAcceso = $query->orderBy('fecha_hora', 'desc')->paginate(15);
        $this->resetPage(); // Resetear paginación al aplicar filtros
    }

    public function limpiarFiltros()
    {
        $this->filtroMiembroId = '';
        $this->filtroSucursalId = '';
        $this->filtroFechaDesde = '';
        $this->filtroFechaHasta = '';
        $this->filtroResultado = '';
        // $this->aplicarFiltros(); // Opcional: aplicar inmediatamente o esperar al botón
        $this->eventosAcceso = EventoAcceso::whereRaw('0=1')->paginate(15); // Paginate vacío
        $this->resetPage();
    }

    // Para reactividad instantánea si se usa wire:model.live
    // public function updated($propertyName)
    // {
    //     if (in_array($propertyName, ['filtroMiembroId', 'filtroSucursalId', 'filtroFechaDesde', 'filtroFechaHasta', 'filtroResultado'])) {
    //         $this->aplicarFiltros();
    //     }
    // }

    public function render()
    {
        // Si no se usa updated() para cada filtro, $eventosAcceso se actualiza solo con aplicarFiltros()
        return view('livewire.informe-eventos-acceso', [
            'eventos_list' => $this->eventosAcceso,
            'all_miembros' => $this->miembros,
            'all_sucursales' => $this->sucursales,
        ]);
    }
}
