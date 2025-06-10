<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AsignarRolesUsuario extends Component
{
    public $searchTermUser = '';
    public $users = [];
    public $selectedUserId;
    public $selectedUser;
    public $roles = [];
    public $userRoles = [];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function updatedSearchTermUser()
    {
        $this->searchUsers();
    }

    public function searchUsers()
    {
        if (strlen($this->searchTermUser) < 3) {
            $this->users = [];
            return;
        }

        $this->users = User::where(function ($query) {
            $query->where('name', 'like', '%'.$this->searchTermUser.'%')
                  ->orWhere('email', 'like', '%'.$this->searchTermUser.'%')
                  // Asumiendo que tienes un campo 'apellido' o 'last_name'
                  // Si no, puedes remover la siguiente línea o ajustarla.
                  ->orWhere('last_name', 'like', '%'.$this->searchTermUser.'%');
        })->take(5)->get(); // Limita a 5 resultados para no sobrecargar
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::find($userId);
        if ($this->selectedUser) {
            $this->userRoles = $this->selectedUser->getRoleNames()->toArray();
        } else {
            $this->userRoles = [];
        }
        $this->users = []; // Limpiar la lista de búsqueda
        $this->searchTermUser = $this->selectedUser ? $this->selectedUser->name : ''; // Mostrar nombre en input
    }

    public function toggleRole($roleName)
    {
        if (!$this->selectedUser) {
            session()->flash('error', 'Ningún usuario seleccionado.');
            return;
        }

        if ($this->selectedUser->hasRole($roleName)) {
            $this->selectedUser->removeRole($roleName);
            session()->flash('message', 'Rol revocado correctamente.');
        } else {
            $this->selectedUser->assignRole($roleName);
            session()->flash('message', 'Rol asignado correctamente.');
        }

        // Actualizar la lista de roles del usuario
        $this->userRoles = $this->selectedUser->getRoleNames()->toArray();
    }

    public function render()
    {
        return view('livewire.asignar-roles-usuario', [
            'users_list' => $this->users,
            'roles_list' => $this->roles,
            'selected_user_info' => $this->selectedUser,
            'user_roles_list' => $this->userRoles,
        ]);
    }
}
