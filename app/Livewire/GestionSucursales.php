<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sucursal;
use Livewire\WithPagination;
use Illuminate\Validation\Rule; // Asegúrate que Rule esté importado

class GestionSucursales extends Component
{
    use WithPagination;

    public string $title = "Gestión de Sucursales";

    // Propiedades para el formulario
    public $sucursalId;
    public $nombre;
    public $direccion;
    public $telefono;
    public $horario_atencion; // Nuevo campo
    // public $logo_path; // Futura implementación

    public $mostrandoModalSucursal = false;
    public $modoEdicionSucursal = false;
    public $mostrandoModalConfirmacionEliminarSucursal = false;
    public $sucursalParaEliminarId;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sucursales', 'nombre')->ignore($this->sucursalId)
            ],
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:25',
            'horario_atencion' => 'nullable|string|max:255', // Regla para el nuevo campo
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre de la sucursal es obligatorio.',
            'nombre.unique' => 'Ya existe una sucursal con este nombre.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.max' => 'El teléfono no debe exceder los 25 caracteres.',
            'horario_atencion.max' => 'El horario de atención no debe exceder los 255 caracteres.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    private function resetInputFieldsSucursal()
    {
        $this->sucursalId = null;
        $this->nombre = '';
        $this->direccion = '';
        $this->telefono = '';
        $this->horario_atencion = ''; // Resetear nuevo campo
        $this->modoEdicionSucursal = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function crearNuevaSucursal()
    {
        $this->authorize('crear sucursal');
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
        $this->authorize('crear sucursal');
        if ($this->modoEdicionSucursal || $this->sucursalId) {
            return $this->actualizarSucursal();
        }

        $validatedData = $this->validate();

        Sucursal::create([
            'nombre' => $validatedData['nombre'],
            'direccion' => $validatedData['direccion'],
            'telefono' => $validatedData['telefono'],
            'horario_atencion' => $validatedData['horario_atencion'], // Guardar nuevo campo
        ]);

        session()->flash('message', 'Sucursal creada exitosamente.');
        $this->cerrarModalSucursal();
    }

    public function editarSucursal(Sucursal $sucursal) // Route Model Binding
    {
        $this->authorize('editar sucursal');
        $this->resetInputFieldsSucursal();

        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        $this->horario_atencion = $sucursal->horario_atencion; // Cargar nuevo campo

        $this->modoEdicionSucursal = true;
        $this->mostrandoModalSucursal = true;
    }

    public function actualizarSucursal()
    {
        $this->authorize('editar sucursal');
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
                'horario_atencion' => $validatedData['horario_atencion'], // Actualizar nuevo campo
            ]);
            session()->flash('message', 'Sucursal actualizada exitosamente.');
        } else {
            session()->flash('error', 'No se encontró la sucursal para actualizar.');
        }
        $this->cerrarModalSucursal();
    }

    public function confirmarEliminacionSucursal($id)
    {
        $this->authorize('eliminar sucursal');
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
        $this->authorize('eliminar sucursal');
        if ($this->sucursalParaEliminarId) {
            $sucursal = Sucursal::find($this->sucursalParaEliminarId);
            if ($sucursal) {
                if ($sucursal->miembros()->exists() || $sucursal->dispositivosControlAcceso()->exists() || $sucursal->users()->exists()) {
                    session()->flash('error', 'Esta sucursal no se puede eliminar porque tiene miembros, dispositivos o usuarios del sistema asociados. Reasígnelos o elimínelos primero.');
                    $this->ocultarModalConfirmacionEliminarSucursal();
                    return;
                }
                try {
                    $sucursal->delete();
                    session()->flash('message', 'Sucursal eliminada exitosamente.');
                } catch (\Illuminate\Database\QueryException $e) {
                    session()->flash('error', 'No se pudo eliminar la sucursal. Error de base de datos.');
                }
            } else {
                session()->flash('error', 'No se encontró la sucursal para eliminar.');
            }
            $this->ocultarModalConfirmacionEliminarSucursal();
        }
    }

    public function render()
    {
        $this->authorize('ver lista sucursales');
        $sucursales = Sucursal::orderBy('nombre')->paginate(10);
        return view('livewire.gestion-sucursales', [
            'sucursales_list' => $sucursales, // Cambiado para evitar colisión con $this->sucursales
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
