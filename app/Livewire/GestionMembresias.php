<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\Membresia;
use App\Models\Sucursal;
use App\Models\TipoMembresia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash; // For codigo_acceso if we hash it
use Livewire\WithPagination; // For pagination

class GestionMembresias extends Component
{
    use WithPagination;

    public string $title = "Gestión de Miembros y Membresías";

    // Propiedades para el formulario
    public $miembroSeleccionadoId;
    public $nombre, $apellido, $email, $telefono, $fecha_nacimiento, $direccion;
    public $sucursal_id;
    public $tipo_membresia_id;
    public $fecha_inicio_membresia;
    // public $foto_path; // Descomentar si se usa WithFileUploads

    public $mostrandoModalRegistro = false;

    // Datos para selectores
    public $sucursales;
    public $tiposMembresia;

    // Para búsqueda y filtros (se pueden añadir más adelante)
    public $search = '';
    public $filtroEstado = '';
    public $filtroTipoMembresia = '';
    public $filtroSucursal = '';

    protected $paginationTheme = 'tailwind'; // Use Tailwind theme for pagination

    public function mount()
    {
        $this->sucursales = Sucursal::orderBy('nombre')->get();
        $this->tiposMembresia = TipoMembresia::orderBy('nombre')->get();
        $this->fecha_inicio_membresia = now()->format('Y-m-d');
        $this->resetInputFields(); // Ensure fields are clean on initial load
    }

    protected function rules()
    {
        $emailRule = 'required|email|unique:miembros,email';
        if ($this->miembroSeleccionadoId) {
            $emailRule .= ',' . $this->miembroSeleccionadoId;
        }

        return [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => $emailRule,
            'telefono' => 'nullable|string|max:25',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'nullable|string|max:255',
            'sucursal_id' => 'required|exists:sucursales,id',
            'tipo_membresia_id' => 'required|exists:tipos_membresia,id',
            'fecha_inicio_membresia' => 'required|date',
            // 'foto_path' => 'nullable|image|max:1024', // Example if using file uploads
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mostrarModalRegistroMiembro()
    {
        $this->resetInputFields();
        $this->mostrandoModalRegistro = true;
    }

    public function ocultarModalRegistroMiembro()
    {
        $this->mostrandoModalRegistro = false;
        $this->resetInputFields();
        $this->resetErrorBag(); // Clear validation errors when closing modal
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
        $this->sucursal_id = $this->sucursales->first()->id ?? null; // Default to first sucursal
        $this->tipo_membresia_id = $this->tiposMembresia->first()->id ?? null; // Default to first tipo membresia
        $this->fecha_inicio_membresia = now()->format('Y-m-d');
        // $this->foto_path = null;
    }

    public function guardarMiembro()
    {
        $validatedData = $this->validate();

        // Lógica para foto_path si se implementa
        // if ($this->foto_path) {
        //     $validatedData['foto_path'] = $this->foto_path->store('fotos_miembros', 'public');
        // }

        $miembro = Miembro::create([
            'nombre' => $validatedData['nombre'],
            'apellido' => $validatedData['apellido'],
            'email' => $validatedData['email'],
            'telefono' => $validatedData['telefono'],
            'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
            'direccion' => $validatedData['direccion'],
            'sucursal_id' => $validatedData['sucursal_id'],
            'codigo_acceso_numerico' => (string) rand(100000, 999999), // Placeholder
            // 'foto_path' => $validatedData['foto_path'] ?? null,
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
                'estado' => 'activa', // Asumir activa al crear
            ]);
        }

        session()->flash('message', 'Miembro y membresía registrados exitosamente.');
        $this->ocultarModalRegistroMiembro();
    }

    public function render()
    {
        // TODO: Implementar filtros y búsqueda
        $miembros = Miembro::with(['sucursal', 'membresias' => function ($query) {
            $query->orderBy('fecha_fin', 'desc');
        }])
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })
        // TODO: Add filters for estado, tipo_membresia, sucursal
        ->latest()
        ->paginate(10);

        return view('livewire.gestion-membresias', [
            'miembros' => $miembros,
        ])->layout('layouts.app', ['title' => $this->title]);
    }
}
