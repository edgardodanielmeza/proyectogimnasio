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
    public $rolActualPermisos; // Guardará el objeto Role para el modal de permisos

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nombreRol' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->rolId)
            ],
            'permisosSeleccionados' => 'array' // Para el modal de permisos
        ];
    }

    public function mount()
    {
        // Cargar todos los permisos una vez
        $this->todosLosPermisos = Permission::orderBy('name')->get()->pluck('name', 'name'); // Usar name como key y value para facilidad en el checkbox list
    }

    public function render()
    {
        $this->authorize('ver lista roles');

        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            // ->where('name', '!=', 'Admin') // Opcional: no mostrar rol Admin en la lista para prevenir su edicion/eliminacion desde aqui
            ->paginate(10);

        return view('livewire.gestion-roles', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $this->authorize('crear rol');
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->isPermissionsModalOpen = false;
        $this->resetErrorBag(); // Limpiar errores de validación al abrir modal
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
        $this->rolActualPermisos = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $permission = $this->rolId ? 'editar rol' : 'crear rol';
        $this->authorize($permission);

        $this->validateOnly('nombreRol'); // Validar solo el nombre del rol aquí

        Role::updateOrCreate(
            ['id' => $this->rolId],
            ['name' => $this->nombreRol, 'guard_name' => 'web']
        );

        session()->flash('message',
            $this->rolId ? 'Rol actualizado exitosamente.' : 'Rol creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit(Role $role) // Route Model Binding
    {
        $this->authorize('editar rol');

        if ($role->name === 'Admin' && auth()->user()->hasRole('Admin') && Role::where('name', 'Admin')->first()->users()->count() <= 1 && $role->users()->where('id', auth()->id())->exists()) {
            // Si el usuario actual es el único Admin, no debería poder editar el nombre del rol Admin
            // O simplemente, no permitir editar el rol Admin en absoluto si se prefiere.
            // Esta lógica es para proteger al Admin, pero se puede simplificar a no editar 'Admin'.
             if (auth()->user()->id !== $role->users()->first()->id) { // si no es el mismo admin
                // no hacer nada
             } else if ($role->name === 'Admin') {
                session()->flash('error', 'El rol Admin no puede ser editado.');
                return;
            }
        }


        $this->rolId = $role->id;
        $this->nombreRol = $role->name;
        $this->rolActualPermisos = $role; // Guardar el rol para el modal
        $this->openModal();
    }

    public function delete(Role $role)
    {
        $this->authorize('eliminar rol');

        if ($role->name === 'Admin') {
            session()->flash('error', 'El rol Admin no puede ser eliminado.');
            return;
        }

        if ($role->users()->count() > 0) {
            session()->flash('error', 'Este rol tiene usuarios asignados y no puede ser eliminado.');
            return;
        }

        if ($role->permissions()->count() > 0) {
             // Opcional: Advertir o impedir si tiene permisos y se prefiere quitarlos manualmente primero
             // $role->syncPermissions([]); // Desvincular todos los permisos antes de eliminar
        }

        $role->delete();
        session()->flash('message', 'Rol eliminado exitosamente.');
    }

    // --- Gestión de Permisos para un Rol ---

    public function openPermissionsModal(Role $role)
    {
        $this->authorize('asignar permisos a rol');

        $this->resetInputFields(); // Limpiar campos del modal de rol si estuviera abierto
        $this->rolId = $role->id; // Necesario para guardarPermisos
        $this->nombreRol = $role->name; // Para mostrar en el título del modal
        $this->rolActualPermisos = $role;
        $this->permisosSeleccionados = $role->permissions->pluck('name')->toArray(); // Usar pluck('name') para que coincida con las keys de $todosLosPermisos

        $this->isPermissionsModalOpen = true;
        $this->isOpen = false;
    }

    public function closePermissionsModal()
    {
        $this->isPermissionsModalOpen = false;
        $this->resetInputFields(); // Limpia rolId, nombreRol, permisosSeleccionados, rolActualPermisos
    }

    public function guardarPermisos()
    {
        $this->authorize('asignar permisos a rol');
        $this->validateOnly('permisosSeleccionados');

        $roleToUpdate = Role::findOrFail($this->rolId); // Usar $this->rolId que se seteó en openPermissionsModal

        if ($roleToUpdate->name === 'Admin') {
            session()->flash('error', 'Los permisos del rol Admin se asignan automáticamente y no pueden ser modificados manualmente.');
            // $roleToUpdate->syncPermissions(Permission::all()); // Re-sincronizar todos por si acaso
            $this->closePermissionsModal();
            return;
        }

        // $this->permisosSeleccionados ya tiene los nombres de los permisos
        $permissionsToSync = Permission::whereIn('name', $this->permisosSeleccionados)->get();
        $roleToUpdate->syncPermissions($permissionsToSync);

        session()->flash('message', 'Permisos actualizados para el rol ' . $roleToUpdate->name);
        $this->closePermissionsModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
