<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

class GestionClases extends Component
{
    public string $title = "Gestión de Clases y Horarios";

    public function render()
    {
        return view('livewire.gestion-clases')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
