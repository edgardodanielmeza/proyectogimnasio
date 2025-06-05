<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\Membresia;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
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
    public $foto;
    public $foto_actual_path;

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

    // --- Control de Modales Principales ---
    public function mostrarModalRegistroMiembro() { /* ... (ya consolidado) ... */ }
    public function ocultarModalRegistroMiembro() { /* ... (ya consolidado) ... */ }
    public function confirmarEliminacionMiembro($id) { /* ... (ya consolidado) ... */ }
    public function ocultarModalConfirmacionEliminar() { /* ... (ya consolidado) ... */ }
    private function resetInputFields() { /* ... (ya consolidado, incluye reset de nuevaMembresia_*) ... */ }

    // --- Operaciones CRUD Miembro ---
    public function guardarMiembro() { /* ... (ya consolidado) ... */ }
    public function editarMiembro($id) { /* ... (ya consolidado) ... */ }
    public function actualizarMiembro() { /* ... (ya consolidado) ... */ }
    public function eliminarMiembro() { /* ... (ya consolidado) ... */ }


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
            $this->membresiaParaCancelarInfo = $membresia->tipoMembresia->nombre . " (Fin: " . Carbon::parse($membresia->fecha_fin)->format('d/m/Y') . ")";
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

    public function guardarNuevaMembresiaParaMiembro() { /* ... (ya consolidado) ... */ }

    public function render()
    {
        $miembros = Miembro::with([
            'sucursal',
            'membresiaActivaActual.tipoMembresia',
            'ultimaMembresiaGeneral.tipoMembresia'
        ])
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
