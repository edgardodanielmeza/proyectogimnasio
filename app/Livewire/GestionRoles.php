<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;

class GestionRoles extends Component
{
    use WithPagination;

    public $roles;
    public $role_id;
    public $name;
    public $guard_name;
    public $isOpen = false;
    public $searchTerm;

    public function render()
    {
        $this->roles = Role::where('name', 'like', '%'.$this->searchTerm.'%')
                            ->paginate(10);
        return view('livewire.gestion-roles', [
            'roles_list' => $this->roles,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->guard_name = '';
        $this->role_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role_id,
            'guard_name' => 'nullable|string',
        ]);

        Role::updateOrCreate(['id' => $this->role_id], [
            'name' => $this->name,
            'guard_name' => $this->guard_name ?? 'web'
        ]);

        session()->flash('message',
            $this->role_id ? 'Rol actualizado correctamente.' : 'Rol creado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $this->role_id = $id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->openModal();
    }

    public function delete($id)
    {
        Role::find($id)->delete();
        session()->flash('message', 'Rol eliminado correctamente.');
    }
}
