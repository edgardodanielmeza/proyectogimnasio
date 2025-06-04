<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\Membresia;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // For codigo_acceso if we hash it (optional)
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class GestionMembresias extends Component
{
    use WithPagination, WithFileUploads;

    public string $title = "Gestión de Miembros y Membresías";

    // Propiedades para el formulario de Miembro
    public $miembroSeleccionadoId = null;
    public $nombre, $apellido, $email, $telefono, $fecha_nacimiento, $direccion;
    public $sucursal_id;
    public $foto; // Para la nueva foto subida (Livewire\TemporaryUploadedFile)
    public $foto_actual_path; // Para mostrar la ruta de la foto existente al editar

    // Propiedades para la membresía inicial (solo en creación)
    public $tipo_membresia_id;
    public $fecha_inicio_membresia;

    // Control de Modales
    public $mostrandoModalRegistro = false;
    public $mostrandoModalConfirmacionEliminar = false;
    public $miembroParaEliminarId;

    // Datos para selectores de formulario
    public $sucursales;
    public $tiposMembresia; // Para el selector en el modal de creación de miembro

    // Búsqueda y Filtros
    public $search = '';
    // public $filtroEstado = ''; // Placeholder para futuros filtros
    // public $filtroTipoMembresiaList = ''; // Placeholder
    // public $filtroSucursalList = ''; // Placeholder

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->sucursales = Sucursal::orderBy('nombre')->get();
        $this->tiposMembresia = TipoMembresia::orderBy('nombre')->get(); // Para el selector de membresía inicial
        $this->fecha_inicio_membresia = now()->format('Y-m-d'); // Default para nueva membresía
        $this->resetInputFields(); // Asegurar estado limpio inicial
    }

    protected function rules()
    {
        $emailRule = 'required|email|unique:miembros,email';
        if ($this->miembroSeleccionadoId) {
            $emailRule .= ',' . $this->miembroSeleccionadoId;
        }

        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => $emailRule,
            'telefono' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'nullable|string|max:255',
            'sucursal_id' => 'required|exists:sucursales,id',
            'foto' => 'nullable|image|max:2048', // Max 2MB, opcional
        ];

        // Reglas específicas para la creación de un nuevo miembro (incluye membresía inicial)
        if (!$this->miembroSeleccionadoId) {
            $rules['tipo_membresia_id'] = 'required|exists:tipos_membresia,id';
            $rules['fecha_inicio_membresia'] = 'required|date';
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.unique' => 'Este email ya está registrado.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'sucursal_id.required' => 'Debe seleccionar una sucursal.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.max' => 'La foto no debe exceder los 2MB.',
            'tipo_membresia_id.required' => 'Debe seleccionar un tipo de membresía.',
            'fecha_inicio_membresia.required' => 'La fecha de inicio de la membresía es obligatoria.',
        ];
    }

    public function updated($propertyName)
    {
        // Real-time validation for 'foto' to show preview, if needed for other fields too
        if ($propertyName === 'foto') {
             $this->validateOnly($propertyName, ['foto' => 'nullable|image|max:2048']);
        } else {
            // Avoid validating on every keystroke for other fields unless specifically needed
            // $this->validateOnly($propertyName);
        }
    }

    // --- Control de Modales ---
    public function mostrarModalRegistroMiembro()
    {
        $this->resetInputFields();
        $this->mostrandoModalRegistro = true;
    }

    public function ocultarModalRegistroMiembro()
    {
        $this->mostrandoModalRegistro = false;
        $this->resetInputFields();
    }

    public function confirmarEliminacionMiembro($id)
    {
        $this->miembroParaEliminarId = $id;
        $this->mostrandoModalConfirmacionEliminar = true;
    }

    public function ocultarModalConfirmacionEliminar()
    {
        $this->mostrandoModalConfirmacionEliminar = false;
        $this->miembroParaEliminarId = null;
    }

    private function resetInputFields()
    {
        $this->miembroSeleccionadoId = null;
        $this->nombre = '';
        $this->apellido = '';
        $this->email = '';
        $this->telefono = '';
        $this->fecha_nacimiento = '';
        $this->direccion = '';
        $this->sucursal_id = $this->sucursales->first()->id ?? null;
        $this->tipo_membresia_id = $this->tiposMembresia->first()->id ?? null;
        $this->fecha_inicio_membresia = now()->format('Y-m-d');
        $this->foto = null;
        $this->foto_actual_path = null;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // --- Operaciones CRUD ---
    public function guardarMiembro()
    {
        if ($this->miembroSeleccionadoId) {
            return $this->actualizarMiembro();
        }

        $validatedData = $this->validate(); // This will use rules() which includes membership fields for creation

        $rutaFoto = null;
        if ($this->foto) {
            $rutaFoto = $this->foto->store('fotos_miembros', 'public');
        }

        $miembro = Miembro::create([
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'email' => $validatedData['email'],
            'telefono' => $validatedData['telefono'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'direccion' => $validatedData['direccion'],
            'sucursal_id' => $validatedData['sucursal_id'],
            'codigo_acceso_numerico' => (string) rand(100000, 999999),
            'foto_path' => $rutaFoto,
        ]);

        $tipoMembresiaSeleccionado = TipoMembresia::find($validatedData['tipo_membresia_id']);
        if ($tipoMembresiaSeleccionado) {
            $fechaFin = Carbon::parse($validatedData['fecha_inicio_membresia'])
                                ->addDays($tipoMembresiaSeleccionado->duracion_dias)
                                ->format('Y-m-d');

            $miembro->membresias()->create([
                'tipo_membresia_id' => $validatedData['tipo_membresia_id'],
                'fecha_inicio' => $validatedData['fecha_inicio_membresia'],
                'fecha_fin' => $fechaFin,
                'estado' => 'activa',
            ]);
        }
        session()->flash('message', 'Miembro y membresía registrados exitosamente.');
        $this->ocultarModalRegistroMiembro();
    }

    public function editarMiembro($id)
    {
        $miembro = Miembro::findOrFail($id);
        $this->miembroSeleccionadoId = $miembro->id;
        $this->nombre = $miembro->nombre;
        $this->apellido = $miembro->apellido;
        $this->email = $miembro->email;
        $this->telefono = $miembro->telefono;
        $this->fecha_nacimiento = $miembro->fecha_nacimiento ? Carbon::parse($miembro->fecha_nacimiento)->format('Y-m-d') : null;
        $this->direccion = $miembro->direccion;
        $this->sucursal_id = $miembro->sucursal_id;
        $this->foto_actual_path = $miembro->foto_path;
        $this->foto = null;

        // Reset membership fields as they are not edited here
        $this->tipo_membresia_id = $this->tiposMembresia->first()->id ?? null;
        $this->fecha_inicio_membresia = now()->format('Y-m-d');

        $this->resetErrorBag();
        $this->mostrandoModalRegistro = true;
    }

    public function actualizarMiembro()
    {
        if (!$this->miembroSeleccionadoId) {
            session()->flash('error', 'Error al actualizar: No hay miembro seleccionado.');
            $this->ocultarModalRegistroMiembro();
            return;
        }

        // For update, we don't validate 'tipo_membresia_id' and 'fecha_inicio_membresia'
        // as they are part of the initial creation or separate membership management.
        $updateRules = $this->rules();
        unset($updateRules['tipo_membresia_id']);
        unset($updateRules['fecha_inicio_membresia']);
        $validatedData = $this->validate($updateRules);

        $miembro = Miembro::find($this->miembroSeleccionadoId);

        if ($miembro) {
            // Using $validatedData ensures only validated fields are used for update
            $updateData = [
                'nombre' => $validatedData['nombre'],
                'apellido' => $validatedData['apellido'],
                'email' => $validatedData['email'],
                'telefono' => $validatedData['telefono'],
                'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                'direccion' => $validatedData['direccion'],
                'sucursal_id' => $validatedData['sucursal_id'],
            ];

            if ($this->foto) {
                if ($miembro->foto_path && Storage::disk('public')->exists($miembro->foto_path)) {
                    Storage::disk('public')->delete($miembro->foto_path);
                }
                $updateData['foto_path'] = $this->foto->store('fotos_miembros', 'public');
            }
            // If $this->foto is null, $updateData will not include 'foto_path',
            // so existing foto_path remains unchanged.

            $miembro->update($updateData);
            session()->flash('message', 'Miembro actualizado exitosamente.');
        } else {
            session()->flash('error', 'No se encontró el miembro para actualizar.');
        }
        $this->ocultarModalRegistroMiembro();
    }

    public function eliminarMiembro()
    {
        if ($this->miembroParaEliminarId) {
            $miembro = Miembro::find($this->miembroParaEliminarId);

            if ($miembro) {
                try {
                    if ($miembro->foto_path && Storage::disk('public')->exists($miembro->foto_path)) {
                        Storage::disk('public')->delete($miembro->foto_path);
                    }
                    // Considerar relaciones y borrado en cascada/soft deletes
                    // $miembro->membresias()->delete(); // Si no hay cascade y no se usa soft delete en membresia
                    // $miembro->pagos()->delete(); // Si no hay cascade
                    $miembro->delete();
                    session()->flash('message', 'Miembro eliminado exitosamente.');
                } catch (\Illuminate\Database\QueryException $e) {
                    session()->flash('error', 'No se pudo eliminar el miembro. Puede tener datos asociados (membresías, pagos) que impiden su eliminación directa. Considere desactivarlo o revisar las políticas de borrado.');
                } catch (\Exception $e) {
                    session()->flash('error', 'Ocurrió un error al intentar eliminar el miembro: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'No se encontró el miembro para eliminar.');
            }
            $this->ocultarModalConfirmacionEliminar();
        }
    }

    public function render()
    {
        $miembros = Miembro::with(['sucursal', 'latestMembresia.tipoMembresia'])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })
        ->latest()
        ->paginate(10);

        return view('livewire.gestion-membresias', [
            'miembros' => $miembros,
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
