<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pago;
use App\Models\Miembro;
use App\Models\Membresia; // Para el selector de membresías
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FacturacionPagos extends Component
{
    use WithPagination;

    public string $title = "Facturación y Pagos";
    public $search = '';
    protected $paginationTheme = 'tailwind';

    // Propiedades para el modal de nuevo pago
    public $mostrandoModalNuevoPago = false;
    public $nuevoPago_miembro_id;
    public $nuevoPago_membresia_id_opcional; // Hacerlo opcional
    public $nuevoPago_monto;
    public $nuevoPago_fecha_pago;
    public $nuevoPago_metodo_pago = 'efectivo'; // Default
    public $nuevoPago_referencia_pago;
    // public $nuevoPago_generar_factura = false; // Si se quiere generar factura inmediatamente

    public $listaMiembros = [];
    public $listaMembresiasDelMiembro = []; // Para el select de membresías

    public $metodosDePagoDisponibles = [
        'efectivo' => 'Efectivo',
        'tarjeta_credito' => 'Tarjeta de Crédito',
        'tarjeta_debito' => 'Tarjeta de Débito',
        'transferencia' => 'Transferencia Bancaria',
        'online' => 'Pago Online',
        'otro' => 'Otro',
    ];

    protected function rules()
    {
        return [
            'nuevoPago_miembro_id' => 'required|exists:miembros,id',
            'nuevoPago_membresia_id_opcional' => 'nullable|exists:membresias,id',
            'nuevoPago_monto' => 'required|numeric|min:0.01',
            'nuevoPago_fecha_pago' => 'required|date',
            'nuevoPago_metodo_pago' => 'required|string|in:' . implode(',', array_keys($this->metodosDePagoDisponibles)),
            'nuevoPago_referencia_pago' => 'nullable|string|max:255',
        ];
    }

    protected function messages() {
        return [
            'nuevoPago_miembro_id.required' => 'Debe seleccionar un miembro.',
            'nuevoPago_monto.required' => 'El monto es obligatorio.',
            'nuevoPago_monto.numeric' => 'El monto debe ser un número.',
            'nuevoPago_monto.min' => 'El monto debe ser positivo.',
            'nuevoPago_fecha_pago.required' => 'La fecha de pago es obligatoria.',
            'nuevoPago_metodo_pago.required' => 'El método de pago es obligatorio.',
        ];
    }

    public function mount()
    {
        // Cargar miembros para el selector. Si son muchos, esto debería ser una búsqueda.
        $this->listaMiembros = Miembro::orderBy('apellido')->orderBy('nombre')->get(['id', 'nombre', 'apellido', 'email']);
        $this->nuevoPago_fecha_pago = now()->format('Y-m-d'); // Default a hoy
    }

    public function updatedNuevoPagoMiembroId($miembroId)
    {
        // Cuando el miembro cambia, cargar sus membresías (opcional)
        $this->nuevoPago_membresia_id_opcional = null; // Resetear membresía seleccionada
        if ($miembroId) {
            $this->listaMembresiasDelMiembro = Membresia::where('miembro_id', $miembroId)
                                                    ->with('tipoMembresia')
                                                    ->orderBy('fecha_fin', 'desc')
                                                    ->get();
        } else {
            $this->listaMembresiasDelMiembro = [];
        }
        $this->resetErrorBag('nuevoPago_membresia_id_opcional');
    }


    public function render()
    {
        $this->authorize('ver lista pagos');

        $query = Pago::with(['miembro', 'membresia.tipoMembresia'])
                     ->orderBy('fecha_pago', 'desc')
                     ->orderBy('id', 'desc'); // Usar ID como segundo criterio para orden estable

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('miembro', function ($subQuery) {
                    $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                             ->orWhere('apellido', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('referencia_pago', 'like', '%' . $this->search . '%')
                ->orWhere('metodo_pago', 'like', '%' . $this->search . '%')
                ->orWhere('monto', 'like', '%' . str_replace(',', '.', $this->search) . '%');
            });
        }

        $pagos = $query->paginate(15);

        return view('livewire.facturacion-pagos', [
            'pagos' => $pagos,
        ])->layout('layouts.app', ['title' => $this->title]);
    }

    public function abrirModalNuevoPago()
    {
        $this->authorize('registrar pago');
        $this->resetInputFieldsPago();
        $this->mostrandoModalNuevoPago = true;
    }

    public function cerrarModalNuevoPago()
    {
        $this->mostrandoModalNuevoPago = false;
        $this->resetInputFieldsPago();
    }

    private function resetInputFieldsPago()
    {
        $this->nuevoPago_miembro_id = null;
        $this->nuevoPago_membresia_id_opcional = null;
        $this->nuevoPago_monto = null;
        $this->nuevoPago_fecha_pago = now()->format('Y-m-d');
        $this->nuevoPago_metodo_pago = 'efectivo';
        $this->nuevoPago_referencia_pago = '';
        $this->listaMembresiasDelMiembro = [];
        $this->resetErrorBag();
    }

    public function guardarNuevoPago()
    {
        $this->authorize('registrar pago');
        $validatedData = $this->validate();

        $pago = Pago::create([
            'miembro_id' => $validatedData['nuevoPago_miembro_id'],
            'membresia_id' => $validatedData['nuevoPago_membresia_id_opcional'], // Puede ser null
            'monto' => $validatedData['nuevoPago_monto'],
            'fecha_pago' => $validatedData['nuevoPago_fecha_pago'],
            'metodo_pago' => $validatedData['nuevoPago_metodo_pago'],
            'referencia_pago' => $validatedData['nuevoPago_referencia_pago'],
            'factura_generada' => false, // Por defecto, la factura no se genera automáticamente aquí
            // 'usuario_registra_id' => Auth::id(), // Opcional: guardar quién registró el pago
        ]);

        // Opcional: Si se pagó una membresía específica y el pago la cubre,
        // se podría actualizar el estado de la membresía aquí.
        // Por ejemplo, si la membresía estaba 'pendiente_pago' y ahora está 'activa'.
        // Esto requiere más lógica y definir estados de membresía.

        session()->flash('message', 'Pago registrado exitosamente. ID: ' . $pago->id);
        $this->cerrarModalNuevoPago();
    }
}
