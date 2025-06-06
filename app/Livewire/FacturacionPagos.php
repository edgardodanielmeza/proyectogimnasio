<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

use App\Models\Pago;
use Livewire\WithPagination;

class FacturacionPagos extends Component
{
    use WithPagination;

    public string $title = "Facturación y Pagos";
    public $search = '';
    protected $paginationTheme = 'tailwind';

    // Propiedades para el modal de nuevo pago (se usarán después)
    public $mostrandoModalNuevoPago = false;
    public $miembro_id_pago, $membresia_id_pago, $monto_pago, $metodo_pago, $fecha_pago, $referencia_pago;

    public function render()
    {
        $query = Pago::with(['miembro', 'membresia.tipoMembresia'])
                     ->orderBy('fecha_pago', 'desc')
                     ->orderBy('created_at', 'desc');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('miembro', function ($subQuery) {
                    $subQuery->where('nombre', 'like', '%' . $this->search . '%')
                             ->orWhere('apellido', 'like', '%' . $this->search . '%')
                             ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orWhere('referencia_pago', 'like', '%' . $this->search . '%')
                ->orWhere('monto', 'like', '%' . $this->search . '%'); // Búsqueda simple por monto
            });
        }

        $pagos = $query->paginate(15); // Mostrar 15 pagos por página

        return view('livewire.facturacion-pagos', [
            'pagos' => $pagos,
        ])->layout('layouts.app', ['title' => $this->title]);
    }

    // Métodos para el Modal (se implementarán después)
    public function crearNuevoPago()
    {
        // $this->resetInputFieldsPago(); // Se creará después
        $this->mostrandoModalNuevoPago = true;
    }

    public function cerrarModalNuevoPago()
    {
        $this->mostrandoModalNuevoPago = false;
        // $this->resetInputFieldsPago();
    }

    public function guardarPago()
    {
        // Lógica de guardado
    }

    // private function resetInputFieldsPago() { /* ... */ }
}
