<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Para la foto
use Livewire\WithFileUploads; // Para la foto

class GestionUsuarios extends Component
{
    use WithPagination, WithFileUploads;

    public $userId, $name, $apellido, $email, $sucursal_id, $activo, $password, $password_confirmation;
    public $rolesUsuario = [];
    public $foto_path, $foto_nueva;

    public $todosLosRoles;
    public $todasLasSucursales;

    public $search = '';
    public $isOpen = false;
    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->userId)],
            'sucursal_id' => 'nullable|exists:sucursales,id',
            'activo' => 'boolean',
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'rolesUsuario' => 'array', // Se valida que los roles existan al asignarlos
            'foto_nueva' => 'nullable|image|max:2048', // Máximo 2MB
        ];
    }

    public function mount()
    {
        $this->todosLosRoles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->toArray(); // Excluir Admin para asignación manual masiva si se desea
        // O si se permite asignar Admin:
        // $this->todosLosRoles = Role::pluck('name', 'name')->toArray();
        $this->todasLasSucursales = \App\Models\Sucursal::pluck('nombre', 'id')->toArray();
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%');
        })
        ->with('sucursal', 'roles') // Cargar relaciones para optimizar
        ->paginate(10);

        return view('livewire.gestion-usuarios', [
            'users' => $users,
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
        $this->resetErrorBag(); // Limpiar errores de validación al cerrar
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->apellido = '';
        $this->email = '';
        $this->sucursal_id = null;
        $this->activo = true;
        $this->password = '';
        $this->password_confirmation = '';
        $this->rolesUsuario = [];
        $this->foto_path = null;
        $this->foto_nueva = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $permission = $this->userId ? 'editar usuario' : 'crear usuario';
        $this->authorize($permission);

        // Adicionalmente, si se están asignando roles, verificar ese permiso si es diferente
        if (!empty($this->rolesUsuario) && auth()->user()->cannot('asignar roles')) {
             // Si el usuario no puede asignar roles pero está intentando hacerlo.
             // Esto es una doble capa, ya que la UI debería ocultar el campo de roles.
             // Podríamos lanzar una excepción o un mensaje de error.
             // Forzamos a que no se modifiquen los roles si no tiene permiso.
             unset($this->rolesUsuario); // O cargar los roles existentes para no cambiarlos.
             session()->flash('error', 'No tienes permiso para asignar roles.');
             // Alternativamente, si la lógica de `syncRoles` abajo maneja bien no tener `rolesUsuario`,
             // esta comprobación podría ser menos estricta aquí.
        }


        $this->validate();

        $data = [
            'name' => $this->name,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'sucursal_id' => $this->sucursal_id,
            'activo' => $this->activo,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->foto_nueva) {
            // Eliminar foto anterior si existe y se está actualizando una nueva
            if ($this->userId) {
                $userOld = User::find($this->userId);
                if ($userOld && $userOld->foto_path) {
                    Storage::disk('public')->delete($userOld->foto_path);
                }
            }
            $data['foto_path'] = $this->foto_nueva->store('fotos_usuarios', 'public');
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);

        // Sincronizar roles, asegurándose de no quitar el rol 'Admin' a un admin si se está editando a sí mismo (o a otro admin)
        // y el rol 'Admin' no fue explícitamente deseleccionado (o si se impide deseleccionarlo desde la UI)
        $adminRole = Role::where('name', 'Admin')->first();
        $currentRoles = $user->roles->pluck('name')->toArray();
        $newRoles = $this->rolesUsuario;

        if ($user->id === auth()->id() && $user->hasRole('Admin') && !in_array('Admin', $newRoles)) {
            // Prevenir que un admin se quite a sí mismo el rol Admin accidentalmente
            // Opcionalmente, se puede impedir la edición de roles para el usuario Admin en la UI
            session()->flash('error', 'Un administrador no puede quitarse su propio rol de Admin.');
        } elseif (in_array('Admin', $currentRoles) && !in_array('Admin', $newRoles) && $user->id !== auth()->id() && $adminRole && !auth()->user()->hasRole('Admin')) {
            // Prevenir que un no-admin quite el rol Admin a otro usuario
             // Esta lógica puede ser más compleja dependiendo de las reglas de negocio
        }
        else {
            $rolesToSync = Role::whereIn('name', $newRoles)->get();
            $user->syncRoles($rolesToSync);
        }


        session()->flash('message',
            $this->userId ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->authorize('editar usuario');
        $user = User::with('roles', 'sucursal')->findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->apellido = $user->apellido;
        $this->email = $user->email;
        $this->sucursal_id = $user->sucursal_id;
        $this->activo = $user->activo;
        $this->rolesUsuario = $user->roles->pluck('name')->toArray();
        $this->foto_path = $user->foto_path;
        $this->password = ''; // No cargar hash de contraseña
        $this->password_confirmation = '';
        $this->openModal();
    }

    public function delete($id)
    {
        $this->authorize('eliminar usuario');
        $user = User::findOrFail($id);

        // Prevenir auto-eliminación
        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta de usuario.');
            return;
        }

        // Prevenir eliminar al único usuario Admin (o una lógica más robusta)
        if ($user->hasRole('Admin')) {
            $adminCount = Role::where('name', 'Admin')->first()->users()->count();
            if ($adminCount <= 1) {
                session()->flash('error', 'No se puede eliminar el último administrador del sistema.');
                return;
            }
        }

        // Eliminar foto de perfil si existe
        if ($user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
        }

        $user->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }

    public function removePhoto()
    {
        if ($this->userId && $this->foto_path) {
            Storage::disk('public')->delete($this->foto_path);
            User::where('id', $this->userId)->update(['foto_path' => null]);
            $this->foto_path = null;
            session()->flash('message', 'Foto de perfil eliminada.');
        }
        $this->foto_nueva = null; // Limpiar si había una nueva seleccionada
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }
}
