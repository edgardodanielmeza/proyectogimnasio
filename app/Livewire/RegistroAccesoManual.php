<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

class RegistroAccesoManual extends Component
{
    public string $title = "Control de Acceso Manual";

    public function render()
    {
        return view('livewire.registro-acceso-manual')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
