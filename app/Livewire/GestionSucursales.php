<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sucursal; // Importar el modelo
use Livewire\WithPagination;

class GestionSucursales extends Component
{
    use WithPagination;

    public $title = "Gestión de Sucursales";

    // Propiedades para el formulario
    public $sucursalId;
    public $nombre;
    public $direccion;
    public $telefono;
    // public $logo_path;

    // Propiedades para controlar modales
    public $mostrandoModalSucursal = false;
    public $modoEdicionSucursal = false;
    public $mostrandoModalConfirmacionEliminarSucursal = false;
    public $sucursalParaEliminarId;

    public $searchSucursales = ''; // Propiedad para la búsqueda

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('sucursales', 'nombre')->ignore($this->sucursalId)
            ],
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:25',
            // 'logo_path' => 'nullable|image|max:1024'
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre de la sucursal es obligatorio.',
            'nombre.unique' => 'Ya existe una sucursal con este nombre.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.max' => 'El teléfono no debe exceder los 25 caracteres.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        // Inicializaciones si son necesarias
    }

    private function resetInputFieldsSucursal()
    {
        $this->sucursalId = null;
        $this->nombre = '';
        $this->direccion = '';
        $this->telefono = '';
        // $this->logo_path = null;
        $this->modoEdicionSucursal = false;
        // $this->searchSucursales = ''; // No resetear la búsqueda principal con el modal
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function crearNuevaSucursal()
    {
        $this->resetInputFieldsSucursal();
        $this->modoEdicionSucursal = false;
        $this->mostrandoModalSucursal = true;
    }

    public function cerrarModalSucursal()
    {
        $this->mostrandoModalSucursal = false;
        $this->resetInputFieldsSucursal();
    }

    public function guardarSucursal()
    {
        if ($this->modoEdicionSucursal || $this->sucursalId) {
            return $this->actualizarSucursal();
        }
        $validatedData = $this->validate();
        Sucursal::create([
            'nombre' => $validatedData['nombre'],
            'direccion' => $validatedData['direccion'],
            'telefono' => $validatedData['telefono'],
        ]);
        session()->flash('message', 'Sucursal creada exitosamente.');
        $this->cerrarModalSucursal();
    }

    public function editarSucursal($id)
    {
        $sucursal = Sucursal::findOrFail($id);
        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->modoEdicionSucursal = true;
        $this->mostrandoModalSucursal = true;
        $this->resetErrorBag();
    }

    public function actualizarSucursal()
    {
        if (!$this->modoEdicionSucursal || !$this->sucursalId) {
            session()->flash('error', 'Error al intentar actualizar. Modo o ID incorrecto.');
            $this->cerrarModalSucursal();
            return;
        }
        $validatedData = $this->validate();
        $sucursal = Sucursal::find($this->sucursalId);
        if ($sucursal) {
            $sucursal->update([
                'nombre' => $validatedData['nombre'],
                'direccion' => $validatedData['direccion'],
                'telefono' => $validatedData['telefono'],
            ]);
            session()->flash('message', 'Sucursal actualizada exitosamente.');
        } else {
            session()->flash('error', 'No se encontró la sucursal para actualizar.');
        }
        $this->cerrarModalSucursal();
    }

    public function confirmarEliminacionSucursal($id)
    {
        $this->sucursalParaEliminarId = $id;
        $this->mostrandoModalConfirmacionEliminarSucursal = true;
    }

    public function ocultarModalConfirmacionEliminarSucursal()
    {
        $this->mostrandoModalConfirmacionEliminarSucursal = false;
        $this->sucursalParaEliminarId = null;
    }

    public function eliminarSucursal()
    {
        if ($this->sucursalParaEliminarId) {
            $sucursal = Sucursal::find($this->sucursalParaEliminarId);
            if ($sucursal) {
                if ($sucursal->miembros()->exists() || $sucursal->dispositivosControlAcceso()->exists() || $sucursal->usuariosSistema()->exists()) {
                    $relatedData = [];
                    if ($sucursal->miembros()->exists()) $relatedData[] = "miembros";
                    if ($sucursal->dispositivosControlAcceso()->exists()) $relatedData[] = "dispositivos de control";
                    if ($sucursal->usuariosSistema()->exists()) $relatedData[] = "usuarios del sistema";
                    session()->flash('error', 'Esta sucursal no se puede eliminar porque tiene ' . implode(', ', $relatedData) . ' asociados. Reasígnelos primero.');
                    $this->ocultarModalConfirmacionEliminarSucursal();
                    return;
                }
                try {
                    $sucursal->delete();
                    session()->flash('message', 'Sucursal eliminada exitosamente.');
                } catch (\Illuminate\Database\QueryException $e) {
                    session()->flash('error', 'No se pudo eliminar la sucursal. Error de base de datos: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'No se encontró la sucursal para eliminar.');
            }
            $this->ocultarModalConfirmacionEliminarSucursal();
        }
    }

    public function render()
    {
        $query = Sucursal::orderBy('nombre');

        if (!empty($this->searchSucursales)) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->searchSucursales . '%')
                  ->orWhere('direccion', 'like', '%' . $this->searchSucursales . '%');
                // ->orWhere('telefono', 'like', '%' . $this->searchSucursales . '%');
            });
        }

        $sucursales = $query->paginate(10);

        return view('livewire.gestion-sucursales', [
            'sucursales' => $sucursales,
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
