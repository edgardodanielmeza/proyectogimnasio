<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @include('livewire.partials.session-messages')

                    <div class="flex justify-between items-center mb-4">
                        @can('crear usuario')
                        <x-primary-button wire:click="create()">
                            {{ __('Crear Nuevo Usuario') }}
                        </x-primary-button>
                        @endcan
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuarios..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-1/3">
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sucursal</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activo</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @if ($user->foto_path)
                                                <img src="{{ asset('storage/' . $user->foto_path) }}" alt="Foto de {{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->name }} {{ $user->apellido }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @foreach ($user->roles as $role)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-sky-100 text-sky-800 dark:bg-sky-700 dark:text-sky-100 mr-1 mb-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->sucursal?->nombre ?: 'N/A' }}</td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm text-center">
                                            @if ($user->activo)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">Activo</span>
                                            @else
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-center">
                                            @can('editar usuario')
                                            <x-secondary-button wire:click="edit({{ $user->id }})" title="Editar Usuario">
                                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                            </x-secondary-button>
                                            @endcan
                                            @can('eliminar usuario')
                                                @if (auth()->id() !== $user->id) {{-- No mostrar botón de eliminar para el usuario logueado --}}
                                                <x-danger-button wire:click="delete({{ $user->id }})" wire:confirm="¿Estás seguro de eliminar al usuario {{ $user->name }}? Esta acción no se puede deshacer." class="ml-2" title="Eliminar Usuario">
                                                     <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c-.34-.059-.68-.114-1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                </x-danger-button>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No se encontraron usuarios.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Usuario -->
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title-user" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" wire:click="closeModal" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-6" id="modal-title-user">
                                {{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <!-- Columna Izquierda: Datos Personales y Foto -->
                                <div>
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full input-form-ldark">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                                        <input type="text" wire:model.defer="apellido" id="apellido" class="mt-1 block w-full input-form-ldark">
                                        @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-red-500">*</span></label>
                                        <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full input-form-ldark">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="foto_nueva" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto de Perfil</label>
                                        <input type="file" wire:model="foto_nueva" id="foto_nueva" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-800 file:text-indigo-700 dark:file:text-indigo-200 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-700">
                                        @error('foto_nueva') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="foto_nueva" class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cargando...</div>

                                        @if ($foto_nueva)
                                            <img src="{{ $foto_nueva->temporaryUrl() }}" alt="Previsualización" class="mt-2 h-20 w-20 rounded-full object-cover">
                                        @elseif ($foto_path)
                                            <img src="{{ asset('storage/' . $foto_path) }}" alt="Foto actual" class="mt-2 h-20 w-20 rounded-full object-cover">
                                            @can('editar usuario') {{-- O un permiso específico para eliminar foto --}}
                                            <button type="button" wire:click="removePhoto" class="mt-1 text-xs text-red-500 hover:text-red-700">Eliminar foto</button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>

                                <!-- Columna Derecha: Contraseña, Sucursal, Roles, Activo -->
                                <div>
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña <span class="text-red-500">*</span> {{ $userId ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                                        <input type="password" wire:model.defer="password" id="password" class="mt-1 block w-full input-form-ldark">
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña <span class="text-red-500">*</span></label>
                                        <input type="password" wire:model.defer="password_confirmation" id="password_confirmation" class="mt-1 block w-full input-form-ldark">
                                    </div>

                                    <div class="mt-4">
                                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sucursal Asignada</label>
                                        <select wire:model.defer="sucursal_id" id="sucursal_id" class="mt-1 block w-full input-form-ldark">
                                            <option value="">Sin sucursal asignada</option>
                                            @foreach($todasLasSucursales as $id => $nombreSucursal)
                                                <option value="{{ $id }}">{{ $nombreSucursal }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    @can('asignar roles')
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1 max-h-32 overflow-y-auto p-2 border border-gray-300 dark:border-gray-700 rounded-md">
                                            @foreach($todosLosRolesDisponibles as $roleNameKey => $roleNameDisplay)
                                                @php
                                                    // Lógica para deshabilitar la casilla del rol 'Admin' si el usuario actual no es Admin
                                                    // O si el usuario que se edita es el único Admin y se intenta quitar ese rol.
                                                    $isCurrentUserAdmin = auth()->user()->hasRole('Admin');
                                                    $isEditingSelf = $userId && auth()->id() == $userId;
                                                    $isRoleAdmin = $roleNameKey === 'Admin';

                                                    $disableAdminRoleCheckbox = $isRoleAdmin && !$isCurrentUserAdmin; // No-admin no puede tocar el rol Admin
                                                    if ($isEditingSelf && $isRoleAdmin && $user->hasRole('Admin') && \Spatie\Permission\Models\Role::findByName('Admin')->users()->count() <=1 ) {
                                                        // $disableAdminRoleCheckbox = true; // No se puede quitar el rol Admin si es el único
                                                    }

                                                @endphp
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="role_{{ $roleNameKey }}" wire:model.defer="rolesUsuario" type="checkbox" value="{{ $roleNameKey }}"
                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                                               {{ $disableAdminRoleCheckbox ? 'disabled' : '' }}>
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label for="role_{{ $roleNameKey }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $disableAdminRoleCheckbox ? 'text-gray-400 dark:text-gray-500 cursor-not-allowed' : '' }}">{{ $roleNameDisplay }}</label>
                                                        @if($disableAdminRoleCheckbox && $isRoleAdmin)
                                                            <p class="text-xs text-gray-400 dark:text-gray-500">(Solo un Admin puede asignar este rol)</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('rolesUsuario') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        @error('rolesUsuario.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    @else
                                        @if($userId && $userBeingEdited = \App\Models\User::find($userId))
                                            <div class="mt-4">
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles Asignados</label>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $userBeingEdited->getRoleNames()->implode(', ') ?: 'Ninguno' }}
                                                </p>
                                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">No tienes permiso para modificar roles.</p>
                                            </div>
                                        @endif
                                    @endcan

                                    <div class="mt-6">
                                        <label for="activo" class="flex items-center">
                                            <input type="checkbox" wire:model.defer="activo" id="activo" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700">
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Usuario Activo</span>
                                        </label>
                                        @error('activo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="ml-3">
                                {{ $userId ? 'Actualizar Usuario' : 'Crear Usuario' }}
                            </x-primary-button>
                            <x-secondary-button type="button" wire:click="closeModal()">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .input-form-ldark { /* Renombrado para evitar conflicto si existe otro .input-form */
        border-width: 1px;
        border-color: #D1D5DB; /* gray-300 */
        background-color: white;
        color: #374151; /* gray-700 */
        border-radius: 0.375rem; /* rounded-md */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
    }
    .dark .input-form-ldark {
        border-color: #4B5563; /* dark:border-gray-700 */
        background-color: #374151; /* dark:bg-gray-800 (un poco más claro que el 900 para inputs) */
        color: #D1D5DB; /* dark:text-gray-300 */
    }
    .input-form-ldark:focus {
        border-color: #6366F1; /* focus:border-indigo-500 */
        --tw-ring-color: #6366F1; /* focus:ring-indigo-500 */
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }
    .dark .input-form-ldark:focus {
        border-color: #818CF8; /* dark:focus:border-indigo-600 */
        background-color: #4B5563; /* Un poco más claro al enfocar en modo oscuro */
        --tw-ring-color: #818CF8; /* dark:focus:ring-indigo-600 */
    }
</style>
@endpush
