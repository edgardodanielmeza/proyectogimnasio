<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DispositivoControlAcceso;
use App\Models\Sucursal;
use Livewire\WithPagination;

class GestionDispositivos extends Component
{
    use WithPagination;

    public $dispositivos;
    public $dispositivo_id;
    public $nombre;
    public $tipo;
    public $sucursal_id;
    public $ip_address;
    public $mac_address;
    public $estado;

    public $sucursales;
    public $isOpen = false;
    public $searchTerm = '';

    public $tiposDisponibles = [
        'teclado_numerico' => 'Teclado Numérico',
        'biometrico_huella' => 'Bimétrico Huella',
        'biometrico_facial' => 'Biométrico Facial',
    ];

    public $estadosDisponibles = [
        'activo' => 'Activo',
        'inactivo' => 'Inactivo',
        'mantenimiento' => 'Mantenimiento',
    ];

    public function mount()
    {
        $this->sucursales = Sucursal::all();
    }

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $this->dispositivos = DispositivoControlAcceso::with('sucursal')
            ->where(function($query) use ($searchTerm) {
                $query->where('nombre', 'like', $searchTerm)
                      ->orWhere('ip_address', 'like', $searchTerm)
                      ->orWhere('mac_address', 'like', $searchTerm);
            })
            ->paginate(10);

        return view('livewire.gestion-dispositivos', [
            'dispositivos_list' => $this->dispositivos,
            'all_sucursales' => $this->sucursales,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->dispositivo_id = null;
        $this->nombre = '';
        $this->tipo = '';
        $this->sucursal_id = '';
        $this->ip_address = '';
        $this->mac_address = '';
        $this->estado = '';
        $this->resetErrorBag(); // Limpiar errores de validación
    }

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:' . implode(',', array_keys($this->tiposDisponibles)),
            'sucursal_id' => 'required|exists:sucursales,id',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|mac_address',
            'estado' => 'required|in:' . implode(',', array_keys($this->estadosDisponibles)),
        ];
    }

    public function store()
    {
        $this->validate();

        DispositivoControlAcceso::updateOrCreate(['id' => $this->dispositivo_id], [
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'sucursal_id' => $this->sucursal_id,
            'ip_address' => $this->ip_address,
            'mac_address' => $this->mac_address,
            'estado' => $this->estado,
        ]);

        session()->flash('message',
            $this->dispositivo_id ? 'Dispositivo actualizado correctamente.' : 'Dispositivo registrado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $dispositivo = DispositivoControlAcceso::findOrFail($id);
        $this->dispositivo_id = $id;
        $this->nombre = $dispositivo->nombre;
        $this->tipo = $dispositivo->tipo;
        $this->sucursal_id = $dispositivo->sucursal_id;
        $this->ip_address = $dispositivo->ip_address;
        $this->mac_address = $dispositivo->mac_address;
        $this->estado = $dispositivo->estado;

        $this->openModal();
    }

    public function delete($id)
    {
        DispositivoControlAcceso::find($id)->delete();
        session()->flash('message', 'Dispositivo eliminado correctamente.');
    }
}
