<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

class FacturacionPagos extends Component
{
    public string $title = "Facturación y Pagos";

    public function render()
    {
        return view('livewire.facturacion-pagos')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
