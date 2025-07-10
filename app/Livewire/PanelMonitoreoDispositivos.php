<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DispositivoControlAcceso;
use App\Models\Sucursal;

class PanelMonitoreoDispositivos extends Component
{
    public $dispositivos = [];
    public $sucursales;
    public $filtroSucursalId = ''; // Default a vacío para "Todas"

    public $estadosDisponibles = [
        'activo' => 'Activo',
        'inactivo' => 'Inactivo',
        'mantenimiento' => 'Mantenimiento',
    ];

    // Para mantener consistencia con GestionDispositivos, aunque no se use para editar
    public $tiposDisponibles = [
        'teclado_numerico' => 'Teclado Numérico',
        'biometrico_huella' => 'Bimétrico Huella',
        'biometrico_facial' => 'Biométrico Facial',
    ];

    public function mount()
    {
        $this->sucursales = Sucursal::all();
        $this->cargarDispositivos();
        // $estadosDisponibles y $tiposDisponibles ya están inicializados
    }

    public function cargarDispositivos()
    {
        $query = DispositivoControlAcceso::with('sucursal');

        if (!empty($this->filtroSucursalId)) {
            $query->where('sucursal_id', $this->filtroSucursalId);
        }

        $this->dispositivos = $query->orderBy('sucursal_id')->orderBy('nombre')->get();
    }

    public function updatedFiltroSucursalId()
    {
        $this->cargarDispositivos();
    }

    public function render()
    {
        // $dispositivos ya se carga a través de cargarDispositivos() en mount y updatedFiltroSucursalId
        return view('livewire.panel-monitoreo-dispositivos', [
            'dispositivos_list' => $this->dispositivos, // Pasar con un nombre consistente para la vista
            'all_sucursales' => $this->sucursales,
            'current_filtro_sucursal_id' => $this->filtroSucursalId,
            'estados_mapping' => $this->estadosDisponibles, // Pasar los mappings para la vista
            'tipos_mapping' => $this->tiposDisponibles,
        ]);
    }
}
