<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionRoles extends Component
{
    use WithPagination;

    public $nombreRol, $rolId, $permisosSeleccionados = [];
    public $todosLosPermisos;
    public $search = '';
    public $isOpen = false;
    public $isPermissionsModalOpen = false;
    public $rolActualPermisos;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'nombreRol' => 'required|string|min:3|max:255',
    ];

    public function mount()
    {
        $this->todosLosPermisos = Permission::all()->pluck('name', 'id');
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->where('name', '!=', 'Admin') // Opcional: no permitir editar/eliminar rol Admin
            ->paginate(10);
        return view('livewire.gestion-roles', [
            'roles' => $roles,
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
        $this->isPermissionsModalOpen = false; // Asegurar que el otro modal esté cerrado
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->nombreRol = '';
        $this->rolId = null;
        $this->permisosSeleccionados = [];
        $this->resetErrorBag();
    }

    public function store()
    {
        $permission = $this->rolId ? 'editar rol' : 'crear rol';
        $this->authorize($permission);

        $this->validate([
            'nombreRol' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->rolId)
            ]
        ]);

        Role::updateOrCreate(['id' => $this->rolId], [
            'name' => $this->nombreRol,
            'guard_name' => 'web' // Por defecto para aplicaciones web
        ]);

        session()->flash('message',
            $this->rolId ? 'Rol actualizado exitosamente.' : 'Rol creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->authorize('editar rol');
        $role = Role::findOrFail($id);
        // No permitir editar el rol 'Admin' directamente aquí
        if ($role->name === 'Admin') {
            session()->flash('error', 'El rol Admin no puede ser editado desde aquí.');
            return;
        }
        $this->rolId = $id;
        $this->nombreRol = $role->name;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->authorize('eliminar rol');
        $role = Role::findOrFail($id);
        if ($role->name === 'Admin') {
            session()->flash('error', 'El rol Admin no puede ser eliminado.');
            return;
        }

        // Verificar si el rol tiene usuarios asignados
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Este rol tiene usuarios asignados y no puede ser eliminado.');
            return;
        }

        $role->delete();
        session()->flash('message', 'Rol eliminado exitosamente.');
    }

    // --- Gestión de Permisos para un Rol ---

    public function openPermissionsModal($rolId)
    {
        $this->authorize('asignar permisos a rol'); // O podría ser 'editar rol' si se considera parte de la edición
        $this->rolId = $rolId;
        $role = Role::findOrFail($rolId);
        $this->nombreRol = $role->name;
        $this->permisosSeleccionados = $role->permissions->pluck('name')->toArray();
        $this->rolActualPermisos = $role;
        $this->isPermissionsModalOpen = true;
        $this->isOpen = false; // Asegurar que el otro modal esté cerrado
    }

    public function closePermissionsModal()
    {
        $this->isPermissionsModalOpen = false;
        $this->resetInputFields(); // También resetea permisosSeleccionados
    }

    public function guardarPermisos()
    {
        $this->authorize('asignar permisos a rol'); // O 'editar rol'

        $this->validate([
            'permisosSeleccionados' => 'array'
        ]);

        $role = Role::findOrFail($this->rolId);

        if ($role->name === 'Admin') {
            session()->flash('error', 'Los permisos del rol Admin no pueden ser modificados desde aquí.');
            $this->closePermissionsModal();
            return;
        }

        // Obtener los permisos que realmente existen en la BD para evitar errores
        $permisosExistentes = Permission::whereIn('name', $this->permisosSeleccionados)->get();
        $role->syncPermissions($permisosExistentes);

        session()->flash('message', 'Permisos actualizados para el rol ' . $role->name);
        $this->closePermissionsModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
