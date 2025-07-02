<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Sucursal;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class GestionUsuarios extends Component
{
    use WithPagination, WithFileUploads;

    public $userId, $name, $apellido, $email, $sucursal_id, $activo;
    public $password, $password_confirmation;
    public $rolesUsuario = []; // Almacena los NOMBRES de los roles seleccionados
    public $foto_path, $foto_nueva;

    public $todosLosRolesDisponibles; // Roles que se pueden asignar
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
            'password' => $this->userId ? ['nullable', 'string', 'min:8', 'confirmed'] : ['required', 'string', 'min:8', 'confirmed'], // Requerido solo al crear
            'rolesUsuario' => 'array',
            'rolesUsuario.*' => ['string', Rule::exists('roles', 'name')], // Validar que cada rol exista
            'foto_nueva' => 'nullable|image|max:2048', // Máximo 2MB
        ];
    }

    public function messages() {
        return [
            'rolesUsuario.*.exists' => 'Uno o más roles seleccionados no son válidos.',
            'password.required' => 'La contraseña es obligatoria para nuevos usuarios.',
        ];
    }

    public function mount()
    {
        // Roles que se pueden asignar (excluir Admin si no es Admin quien edita, o manejarlo con cuidado)
        if (auth()->user()->hasRole('Admin')) {
            $this->todosLosRolesDisponibles = Role::pluck('name', 'name')->toArray();
        } else {
            // Si no es Admin, no puede asignar el rol Admin
            $this->todosLosRolesDisponibles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->toArray();
        }
        $this->todasLasSucursales = Sucursal::pluck('nombre', 'id')->toArray();
    }

    public function render()
    {
        $this->authorize('ver lista usuarios');

        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('apellido', 'like', '%' . $this->search . '%');
        })
        ->with('sucursal', 'roles')
        ->paginate(10);

        return view('livewire.gestion-usuarios', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->authorize('crear usuario');
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isOpen = false;
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
        if ($this->foto_nueva) { // Limpiar el archivo temporal si existe
            $this->foto_nueva->delete();
        }
        $this->foto_nueva = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $permission = $this->userId ? 'editar usuario' : 'crear usuario';
        $this->authorize($permission);

        $validatedData = $this->validate();

        $dataToStore = [
            'name' => $validatedData['name'],
            'apellido' => $validatedData['apellido'],
            'email' => $validatedData['email'],
            'sucursal_id' => $validatedData['sucursal_id'],
            'activo' => $validatedData['activo'],
        ];

        if (!empty($validatedData['password'])) {
            $dataToStore['password'] = Hash::make($validatedData['password']);
        }

        if ($this->foto_nueva) {
            if ($this->userId) {
                $userOld = User::find($this->userId);
                if ($userOld && $userOld->foto_path) {
                    Storage::disk('public')->delete($userOld->foto_path);
                }
            }
            $dataToStore['foto_path'] = $this->foto_nueva->store('fotos_usuarios', 'public');
        }

        $user = User::updateOrCreate(['id' => $this->userId], $dataToStore);

        // Gestión de Roles
        if (auth()->user()->can('asignar roles')) {
            $rolesToSync = $validatedData['rolesUsuario']; // Nombres de los roles

            // Prevenir que un Admin se quite a sí mismo el rol Admin si es el único
            if ($user->hasRole('Admin') && !in_array('Admin', $rolesToSync)) {
                $adminRole = Role::where('name', 'Admin')->first();
                if ($adminRole && $adminRole->users()->count() === 1 && $adminRole->users()->first()->id === $user->id) {
                    session()->flash('error', 'No se puede quitar el rol Admin al único administrador.');
                    // Forzar a que mantenga el rol Admin
                    $rolesToSync[] = 'Admin';
                    $rolesToSync = array_unique($rolesToSync); // Evitar duplicados
                }
            }
             // Un usuario no-Admin no puede asignar el rol Admin
            if (!auth()->user()->hasRole('Admin') && in_array('Admin', $rolesToSync)) {
                if ($this->userId && $user->id == $this->userId && $user->hasRole('Admin')) {
                    // Si está editando un Admin y el rol Admin ya estaba, permitirlo (no lo está añadiendo)
                } else {
                    // Si está intentando añadir el rol Admin y no es Admin
                    unset($rolesToSync[array_search('Admin', $rolesToSync)]); // Quitar Admin de la lista
                    session()->flash('error', 'No tienes permiso para asignar el rol Admin.');
                }
            }

            $user->syncRoles($rolesToSync);
        } elseif (!empty($validatedData['rolesUsuario']) && !$this->userId) {
            // Si es un nuevo usuario y se intentaron asignar roles sin permiso,
            // no se asignan roles, pero el usuario se crea.
            session()->flash('error', 'Usuario creado, pero no tienes permiso para asignar roles. Contacta a un administrador.');
        }


        session()->flash('message',
            $this->userId ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit(User $user) // Route Model Binding
    {
        $this->authorize('editar usuario');
        $this->resetInputFields(); // Asegurar limpieza antes de cargar

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->apellido = $user->apellido;
        $this->email = $user->email;
        $this->sucursal_id = $user->sucursal_id;
        $this->activo = $user->activo;
        $this->rolesUsuario = $user->roles->pluck('name')->toArray();
        $this->foto_path = $user->foto_path;
        // No cargar password
        $this->openModal();
    }

    public function delete(User $user)
    {
        $this->authorize('eliminar usuario');

        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta de usuario.');
            return;
        }

        if ($user->hasRole('Admin')) {
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole && $adminRole->users()->count() <= 1) {
                session()->flash('error', 'No se puede eliminar el último administrador del sistema.');
                return;
            }
        }

        if ($user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
        }

        $user->delete(); // Esto debería desvincular roles/permisos automáticamente por Spatie
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }

    public function removePhoto()
    {
        $this->authorize('editar usuario'); // Solo quien puede editar puede quitar foto
        if ($this->userId && $this->foto_path) {
            Storage::disk('public')->delete($this->foto_path);
            User::where('id', $this->userId)->update(['foto_path' => null]);
            $this->foto_path = null;
            session()->flash('message', 'Foto de perfil eliminada.');
        }
        if ($this->foto_nueva) {
            $this->foto_nueva->delete(); // Limpiar si había una nueva seleccionada y no se guardó
        }
        $this->foto_nueva = null;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
