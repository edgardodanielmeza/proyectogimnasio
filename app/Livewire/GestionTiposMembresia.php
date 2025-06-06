<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TipoMembresia;
use Livewire\WithPagination;

class GestionTiposMembresia extends Component
{
    use WithPagination;

    public $title = "Gestión de Tipos de Membresía";

    // Propiedades para el formulario
    public $tipoMembresiaId;
    public $nombre;
    public $descripcion;
    public $duracion_dias;
    public $precio;

    // Control de Modales
    public $mostrandoModal = false;
    public $modoEdicion = false;
    public $mostrandoModalConfirmacionEliminarTipo = false;
    public $tipoMembresiaParaEliminarId;

    public $search = ''; // Propiedad para la búsqueda

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:tipos_membresia,nombre,' . $this->tipoMembresiaId,
            'descripcion' => 'nullable|string|max:1000',
            'duracion_dias' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ];
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre del tipo de membresía es obligatorio.',
            'nombre.unique' => 'Este nombre de tipo de membresía ya existe.',
            'duracion_dias.required' => 'La duración en días es obligatoria.',
            'duracion_dias.integer' => 'La duración debe ser un número entero.',
            'duracion_dias.min' => 'La duración debe ser de al menos 1 día.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
            'precio.min' => 'El precio no puede ser negativo.',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // --- Control de Modales ---
    public function crearNuevoTipoMembresia()
    {
        $this->resetInputFields();
        $this->modoEdicion = false;
        $this->mostrandoModal = true;
    }

    public function cerrarModal()
    {
        $this->mostrandoModal = false;
        $this->resetInputFields(); // Asegura que todo se limpie al cerrar
    }

    public function confirmarEliminacion($id)
    {
        $this->tipoMembresiaParaEliminarId = $id;
        $this->mostrandoModalConfirmacionEliminarTipo = true;
    }

    public function ocultarModalConfirmacionEliminarTipo()
    {
        $this->mostrandoModalConfirmacionEliminarTipo = false;
        $this->tipoMembresiaParaEliminarId = null;
    }

    private function resetInputFields()
    {
        $this->tipoMembresiaId = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->duracion_dias = null;
        $this->precio = null;
        $this->modoEdicion = false;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // --- Operaciones CRUD ---
    public function guardarTipoMembresia()
    {
        // Si hay un ID, debería ser una actualización, pero el flujo de UI debe llamar a actualizarTipoMembresia.
        // Esta guarda es solo para nuevos.
        if($this->modoEdicion || $this->tipoMembresiaId) {
             session()->flash('error', 'Error de flujo: intentando guardar como nuevo durante una edición.');
             $this->cerrarModal();
             return;
        }

        $this->validate();

        TipoMembresia::create([
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'duracion_dias' => $this->duracion_dias,
            'precio' => $this->precio,
        ]);

        session()->flash('message', 'Tipo de membresía creado exitosamente.');
        $this->cerrarModal();
    }

    public function editarTipoMembresia($id)
    {
        $tipoMembresia = TipoMembresia::findOrFail($id);

        $this->tipoMembresiaId = $tipoMembresia->id;
        $this->nombre = $tipoMembresia->nombre;
        $this->descripcion = $tipoMembresia->descripcion;
        $this->duracion_dias = $tipoMembresia->duracion_dias;
        $this->precio = $tipoMembresia->precio;

        $this->modoEdicion = true;
        $this->mostrandoModal = true;
        $this->resetErrorBag();
    }

    public function actualizarTipoMembresia()
    {
        if (!$this->tipoMembresiaId || !$this->modoEdicion) {
            session()->flash('error', 'Error al actualizar: No hay tipo de membresía seleccionado para edición.');
            $this->cerrarModal();
            return;
        }

        $this->validate();

        $tipoMembresia = TipoMembresia::find($this->tipoMembresiaId);

        if ($tipoMembresia) {
            $tipoMembresia->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'duracion_dias' => $this->duracion_dias,
                'precio' => $this->precio,
            ]);

            session()->flash('message', 'Tipo de membresía actualizado exitosamente.');
        } else {
            session()->flash('error', 'No se encontró el tipo de membresía para actualizar.');
        }
        $this->cerrarModal();
    }

    public function eliminarTipoMembresia()
    {
        if ($this->tipoMembresiaParaEliminarId) {
            $tipoMembresia = TipoMembresia::find($this->tipoMembresiaParaEliminarId);

            if ($tipoMembresia) {
                if ($tipoMembresia->membresias()->exists()) {
                    session()->flash('error', 'Este tipo de membresía no se puede eliminar porque está siendo utilizado por una o más membresías de miembros.');
                    $this->ocultarModalConfirmacionEliminarTipo();
                    return;
                }

                try {
                    $tipoMembresia->delete();
                    session()->flash('message', 'Tipo de membresía eliminado exitosamente.');
                } catch (\Illuminate\Database\QueryException $e) {
                    session()->flash('error', 'No se pudo eliminar el tipo de membresía. Error de base de datos: ' . $e->getCode());
                }
            } else {
                session()->flash('error', 'No se encontró el tipo de membresía para eliminar.');
            }
            $this->ocultarModalConfirmacionEliminarTipo();
        }
    }

    public function render()
    {
        $tiposMembresia = TipoMembresia::orderBy('nombre')->paginate(10);

        return view('livewire.gestion-tipos-membresia', [
            'tiposMembresia' => $tiposMembresia,
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
