<?php

namespace App\Livewire; // Corrected namespace

use Livewire\Component;

class DashboardGeneral extends Component
{
    public string $title = "Panel de Control Principal";

    public function render()
    {
        return view('livewire.dashboard-general')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
