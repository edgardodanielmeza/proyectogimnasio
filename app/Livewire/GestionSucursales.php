<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sucursal; // Importar el modelo
use Livewire\WithPagination;



use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // For codigo_acceso if we hash it (optional)

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;






class GestionSucursales extends Component
{
    use WithPagination;

    public $title = "Gestión de Sucursales";

    // Propiedades para el formulario
    public $sucursalId;
    public $nombre;
    public $direccion;
    public $telefono;
    // public $logo_path; // Se podría añadir después si se implementa subida de logo

    // Propiedades para controlar modales
    public $mostrandoModalSucursal = false;
    public $modoEdicionSucursal = false; // Usar un nombre específico para evitar colisiones si se copian modales

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
            // 'logo_path' => 'nullable|image|max:1024' // Para cuando se implemente el logo
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
        // Podríamos inicializar algo aquí si fuera necesario
    }

    private function resetInputFieldsSucursal()
    {
        $this->sucursalId = null;
        $this->nombre = '';
        $this->direccion = '';
        $this->telefono = '';
        // $this->logo_path = null;
        $this->modoEdicionSucursal = false;
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
        $this->resetInputFieldsSucursal(); // Asegurar que se limpien los campos al cerrar
    }

    // Métodos para guardar, editar, actualizar, eliminar (se crearán en pasos posteriores)
    public function guardarSucursal()
    {
        // Asegurarse de que no estamos en modo edición por si acaso
        if ($this->modoEdicionSucursal || $this->sucursalId) {
            // Esto no debería ocurrir si la UI llama al método correcto (actualizarSucursal)
            // pero es una salvaguarda. Podría redirigir a actualizar o simplemente retornar.
            // session()->flash('error', 'Error de flujo: Intento de guardar como nuevo durante una edición.');
            // $this->cerrarModalSucursal();
            return $this->actualizarSucursal(); // Opcionalmente, redirigir la lógica si hay un ID.
        }

        // Validar los datos usando las reglas y mensajes definidos en el componente
        $validatedData = $this->validate(); // Esto usará los métodos rules() y messages()

        // Crear la nueva sucursal
        Sucursal::create([
            'nombre' => $validatedData['nombre'],
            'direccion' => $validatedData['direccion'],
            'telefono' => $validatedData['telefono'],
            // 'logo_path' => $this->logo_path, // Si se implementa subida de logo
        ]);

        // Mostrar mensaje de éxito
        session()->flash('message', 'Sucursal creada exitosamente.');

        // Cerrar el modal y resetear los campos del formulario
        $this->cerrarModalSucursal(); // Este método ya debería llamar a resetInputFieldsSucursal()
    }

    public $mostrandoModalConfirmacionEliminarSucursal = false;
    public $sucursalParaEliminarId;

    public function editarSucursal($id)
    {
        $sucursal = Sucursal::findOrFail($id); // Usar findOrFail para manejar el caso de ID no encontrado

        $this->sucursalId = $sucursal->id;
        $this->nombre = $sucursal->nombre;
        $this->direccion = $sucursal->direccion;
        $this->telefono = $sucursal->telefono;
        // $this->logo_path = $sucursal->logo_path; // Para cuando se implemente el logo

        $this->modoEdicionSucursal = true;
        $this->mostrandoModalSucursal = true;
        $this->resetErrorBag(); // Limpiar errores de validación por si había alguno antes
    }

    public function actualizarSucursal()
    {
        // Asegurarse de que estamos en modo edición y tenemos un ID
        if (!$this->modoEdicionSucursal || !$this->sucursalId) {
            session()->flash('error', 'Error al intentar actualizar. Modo o ID incorrecto.');
            $this->cerrarModalSucursal(); // Cierra el modal y resetea
            return;
        }

        // Validar los datos (las reglas ya están configuradas para manejar la unicidad del nombre al editar)
        $validatedData = $this->validate();

        $sucursal = Sucursal::find($this->sucursalId);

        if ($sucursal) {
            $sucursal->update([
                'nombre' => $validatedData['nombre'],
                'direccion' => $validatedData['direccion'],
                'telefono' => $validatedData['telefono'],
                // 'logo_path' => $this->logo_path, // Si se implementa logo y se actualiza
            ]);

            session()->flash('message', 'Sucursal actualizada exitosamente.');
        } else {
            // Manejar el caso raro de que el ID no se encuentre después de haberlo cargado
            session()->flash('error', 'No se encontró la sucursal para actualizar.');
        }

        $this->cerrarModalSucursal(); // Este método ya resetea los campos, el modoEdicion y oculta el modal
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
                // Verificar relaciones antes de eliminar
                if ($sucursal->miembros()->exists()) {
                    session()->flash('error', 'Esta sucursal no se puede eliminar porque tiene miembros asociados. Reasigne los miembros primero.');
                    $this->ocultarModalConfirmacionEliminarSucursal();
                    return;
                }

                if ($sucursal->dispositivosControlAcceso()->exists()) {
                    session()->flash('error', 'Esta sucursal no se puede eliminar porque tiene dispositivos de control de acceso asociados.');
                    $this->ocultarModalConfirmacionEliminarSucursal();
                    return;
                }

                if ($sucursal->usuariosSistema()->exists()) {
                     session()->flash('error', 'Esta sucursal no se puede eliminar porque tiene usuarios del sistema asignados. Reasígnelos primero.');
                    $this->ocultarModalConfirmacionEliminarSucursal();
                    return;
                }

                try {
                    // Lógica para eliminar logo si existe (se implementará si se añade gestión de logos)
                    // if ($sucursal->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($sucursal->logo_path)) {
                    //     \Illuminate\Support\Facades\Storage::disk('public')->delete($sucursal->logo_path);
                    // }
                    $sucursal->delete(); // Borrado físico
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
        $sucursales = Sucursal::orderBy('nombre') // Ordenar por nombre, por ejemplo
                               ->paginate(10);    // Paginar, 10 por página

        $sucursales->each(function ($sucursal) {
            // Agregar una propiedad 'logo' si se desea mostrar el logo
            $sucursal->logo = $sucursal->logo_path; // Agregar la propiedad 'logo' aquí
        });
        return view('livewire.gestion-sucursales', [
            'sucursales' => $sucursales, // Pasar las sucursales a la vista
        ])->layout('layouts.app', ['title' => $this->title]);
    }








}
