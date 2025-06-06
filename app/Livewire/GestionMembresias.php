<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\Membresia;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // For codigo_acceso if we hash it (optional)
use Illuminate\Validation\Rule; // For more complex validation rules if needed
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
    public $tiposMembresia;

    // Búsqueda y Filtros
    public $search = '';

    // Propiedades para el modal de gestión de membresías del miembro
    public $mostrandoModalGestionMembresiasMiembro = false;
    public $miembroParaGestionarMembresias;
    public $historialMembresias = [];

    // Propiedades para el mini-formulario de añadir nueva membresía
    public $nuevaMembresia_tipo_id;
    public $nuevaMembresia_fecha_inicio;

    // Propiedades para el modal de confirmación de cancelación de membresía
    public $mostrandoModalConfirmacionCancelarMembresia = false;
    public $membresiaParaCancelarId;
    public $membresiaParaCancelarInfo;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->sucursales = Sucursal::orderBy('nombre')->get();
        $this->tiposMembresia = TipoMembresia::orderBy('nombre')->get();
        $this->fecha_inicio_membresia = now()->format('Y-m-d');
        $this->resetInputFields();
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
            'foto' => 'nullable|image|max:2048',
        ];

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
            'tipo_membresia_id.required' => 'Debe seleccionar un tipo de membresía para el nuevo miembro.',
            'fecha_inicio_membresia.required' => 'La fecha de inicio de la membresía es obligatoria para el nuevo miembro.',
            'nuevaMembresia_tipo_id.required' => 'Debe seleccionar un tipo para la nueva membresía.',
            'nuevaMembresia_tipo_id.exists' => 'El tipo de membresía seleccionado no es válido.',
            'nuevaMembresia_fecha_inicio.required' => 'La fecha de inicio es obligatoria para la nueva membresía.',
            'nuevaMembresia_fecha_inicio.date' => 'La fecha de inicio para la nueva membresía no es válida.',
        ];
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'foto') {
             $this->validateOnly($propertyName, ['foto' => 'nullable|image|max:2048']);
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

        // Resetear también los campos del sub-modal de gestión de membresías
        $this->miembroParaGestionarMembresias = null;
        $this->historialMembresias = [];
        $this->nuevaMembresia_tipo_id = $this->tiposMembresia->first()->id ?? null;
        $this->nuevaMembresia_fecha_inicio = now()->format('Y-m-d');

        // Resetear campos del modal de cancelación
        $this->membresiaParaCancelarId = null;
        $this->membresiaParaCancelarInfo = null;

        $this->resetErrorBag();
        $this->resetValidation();
    }

    // --- Operaciones CRUD Miembro ---
    public function guardarMiembro()
    {
        if ($this->miembroSeleccionadoId) {
            return $this->actualizarMiembro();
        }
        $validatedData = $this->validate();
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
            $membresiaCreada = $miembro->membresias()->create([ // Guardar la membresía creada en una variable
                'tipo_membresia_id' => $validatedData['tipo_membresia_id'],
                'fecha_inicio' => $validatedData['fecha_inicio_membresia'],
                'fecha_fin' => $fechaFin,
                'estado' => 'activa',
            ]);

            // ---> INICIO DE NUEVA LÓGICA PARA EL PAGO <---
            if ($membresiaCreada) { // Asegurarse que la membresía se creó
                Pago::create([
                    'miembro_id' => $miembro->id,
                    'membresia_id' => $membresiaCreada->id,
                    'monto' => $tipoMembresiaSeleccionado->precio, // Usar el precio del tipo de membresía
                    'fecha_pago' => now()->format('Y-m-d'), // Asumir pago inmediato
                    'metodo_pago' => 'Inscripción Inicial', // O un valor por defecto como 'Sistema' o 'Efectivo'
                    'referencia_pago' => 'Pago inicial: ' . $tipoMembresiaSeleccionado->nombre,
                    'factura_generada' => false,
                ]);
            }
            // ---> FIN DE NUEVA LÓGICA PARA EL PAGO <---
        }
        session()->flash('message', 'Miembro, membresía y pago inicial registrados exitosamente.');
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
        $updateRules = $this->rules();
        unset($updateRules['tipo_membresia_id']);
        unset($updateRules['fecha_inicio_membresia']);
        $validatedData = $this->validate($updateRules);
        $miembro = Miembro::find($this->miembroSeleccionadoId);
        if ($miembro) {
            $updateDataArr = [
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
                $updateDataArr['foto_path'] = $this->foto->store('fotos_miembros', 'public');
            }
            $miembro->update($updateDataArr);
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
                    $miembro->delete();
                    session()->flash('message', 'Miembro eliminado exitosamente.');
                } catch (\Illuminate\Database\QueryException $e) {
                    session()->flash('error', 'No se pudo eliminar el miembro. Puede tener datos asociados que impiden su eliminación directa.');
                } catch (\Exception $e) {
                    session()->flash('error', 'Ocurrió un error al intentar eliminar el miembro: ' . $e->getMessage());
                }
            } else {
                session()->flash('error', 'No se encontró el miembro para eliminar.');
            }
            $this->ocultarModalConfirmacionEliminar();
        }
    }

    // --- Modal de Gestión de Membresías del Miembro ---
    public function abrirModalGestionMembresias($miembroId)
    {
        $this->miembroParaGestionarMembresias = Miembro::find($miembroId);
        if (!$this->miembroParaGestionarMembresias) {
            session()->flash('error', 'No se encontró el miembro.');
            return;
        }
        $this->historialMembresias = $this->miembroParaGestionarMembresias->membresias()
                                        ->with('tipoMembresia')
                                        ->orderBy('fecha_inicio', 'desc')
                                        ->get();
        $this->nuevaMembresia_tipo_id = $this->tiposMembresia->first()->id ?? null;
        $this->nuevaMembresia_fecha_inicio = now()->format('Y-m-d');
        $this->resetErrorBag(['nuevaMembresia_tipo_id', 'nuevaMembresia_fecha_inicio']);
        $this->mostrandoModalGestionMembresiasMiembro = true;
    }

    public function cerrarModalGestionMembresias()
    {
        $this->mostrandoModalGestionMembresiasMiembro = false;
        $this->miembroParaGestionarMembresias = null;
        $this->historialMembresias = [];
        $this->nuevaMembresia_tipo_id = $this->tiposMembresia->first()->id ?? null;
        $this->nuevaMembresia_fecha_inicio = now()->format('Y-m-d');
        $this->resetErrorBag(['nuevaMembresia_tipo_id', 'nuevaMembresia_fecha_inicio']);
    }

    // --- Acciones dentro del Modal de Gestión de Membresías ---
    public function confirmarCancelacionMembresia($membresiaId)
    {
        $membresia = Membresia::with('tipoMembresia')->find($membresiaId);
        if ($membresia && $membresia->estado == 'activa' && Carbon::parse($membresia->fecha_fin)->gte(Carbon::today())) {
            $this->membresiaParaCancelarId = $membresia->id;
            $this->membresiaParaCancelarInfo = ($membresia->tipoMembresia->nombre ?? 'N/A') . " (Fin: " . Carbon::parse($membresia->fecha_fin)->format('d/m/Y') . ")";
            $this->mostrandoModalConfirmacionCancelarMembresia = true;
        } else {
            session()->flash('error_modal_gestion', 'Esta membresía no se puede cancelar o ya no está activa.');
        }
    }

    public function ocultarModalConfirmacionCancelarMembresia()
    {
        $this->mostrandoModalConfirmacionCancelarMembresia = false;
        $this->membresiaParaCancelarId = null;
        $this->membresiaParaCancelarInfo = null;
    }

    public function ejecutarCancelacionMembresia()
    {
        if (!$this->membresiaParaCancelarId) {
            session()->flash('error_modal_gestion', 'No hay membresía seleccionada para cancelar.');
            $this->ocultarModalConfirmacionCancelarMembresia();
            return;
        }
        $membresia = Membresia::find($this->membresiaParaCancelarId);
        if ($membresia && $membresia->estado == 'activa' && Carbon::parse($membresia->fecha_fin)->gte(Carbon::today())) {
            $membresia->estado = 'cancelada';
            $membresia->save();
            if ($this->miembroParaGestionarMembresias) {
                $this->historialMembresias = $this->miembroParaGestionarMembresias->membresias()
                                                ->with('tipoMembresia')
                                                ->orderBy('fecha_inicio', 'desc')
                                                ->get();
            }
            session()->flash('message_modal_gestion', 'La membresía ha sido cancelada.');
        } else {
            session()->flash('error_modal_gestion', 'No se pudo cancelar la membresía o ya no estaba activa.');
        }
        $this->ocultarModalConfirmacionCancelarMembresia();
    }

    public function prepararRenovacionMembresia($membresiaId)
    {
        $membresiaAnterior = Membresia::find($membresiaId);
        if (!$membresiaAnterior) {
            session()->flash('error_modal_gestion', 'No se encontró la membresía original para renovar.');
            $this->resetErrorBag(['nuevaMembresia_tipo_id', 'nuevaMembresia_fecha_inicio']);
            $this->nuevaMembresia_tipo_id = $this->tiposMembresia->first()->id ?? null;
            $this->nuevaMembresia_fecha_inicio = now()->format('Y-m-d');
            return;
        }
        $this->nuevaMembresia_tipo_id = $membresiaAnterior->tipo_membresia_id;
        $fechaFinAnterior = Carbon::parse($membresiaAnterior->fecha_fin);
        $hoy = Carbon::today();
        if ($fechaFinAnterior->gte($hoy)) {
            $this->nuevaMembresia_fecha_inicio = $fechaFinAnterior->addDay()->format('Y-m-d');
        } else {
            $this->nuevaMembresia_fecha_inicio = $hoy->format('Y-m-d');
        }
        $this->resetErrorBag(['nuevaMembresia_tipo_id', 'nuevaMembresia_fecha_inicio']);
        session()->flash('info_modal_gestion', 'Datos de renovación cargados en el formulario "Añadir Nueva Membresía". Por favor, verifique y presione "Añadir Nueva Membresía" para confirmar.');
    }

    public function guardarNuevaMembresiaParaMiembro()
    {
        if (!$this->miembroParaGestionarMembresias) {
            session()->flash('error_modal_gestion', 'No hay un miembro seleccionado para añadir la membresía.');
            return;
        }
        $validatedData = $this->validate([
            'nuevaMembresia_tipo_id' => 'required|exists:tipos_membresia,id',
            'nuevaMembresia_fecha_inicio' => 'required|date',
        ]);
        $tipoMembresiaSeleccionado = TipoMembresia::find($validatedData['nuevaMembresia_tipo_id']);
        if (!$tipoMembresiaSeleccionado) {
            session()->flash('error_modal_gestion', 'Tipo de membresía no encontrado.');
            return;
        }
        $fechaFin = Carbon::parse($validatedData['nuevaMembresia_fecha_inicio'])
                        ->addDays($tipoMembresiaSeleccionado->duracion_dias)
                        ->format('Y-m-d');
        $this->miembroParaGestionarMembresias->membresias()->create([
            'tipo_membresia_id' => $validatedData['nuevaMembresia_tipo_id'],
            'fecha_inicio' => $validatedData['nuevaMembresia_fecha_inicio'],
            'fecha_fin' => $fechaFin,
            'estado' => 'activa',
        ]);
        session()->flash('message_modal_gestion', 'Nueva membresía añadida exitosamente al miembro.');
        $this->historialMembresias = $this->miembroParaGestionarMembresias->membresias()
                                        ->with('tipoMembresia')
                                        ->orderBy('fecha_inicio', 'desc')
                                        ->get();
        $this->nuevaMembresia_tipo_id = $this->tiposMembresia->first()->id ?? null;
        $this->nuevaMembresia_fecha_inicio = now()->format('Y-m-d');
        $this->resetErrorBag(['nuevaMembresia_tipo_id', 'nuevaMembresia_fecha_inicio']);
    }

    public function render()
    {
        $miembros = Miembro::with(['sucursal', 'membresiaActivaActual.tipoMembresia', 'ultimaMembresiaGeneral.tipoMembresia'])
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
