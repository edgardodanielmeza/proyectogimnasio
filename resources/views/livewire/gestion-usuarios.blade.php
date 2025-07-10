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

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('message') }}</span>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        @can('crear usuario')
                        <x-primary-button wire:click="create()">
                            {{ __('Crear Nuevo Usuario') }}
                        </x-primary-button>
                        @endcan
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuarios..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-1/3">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sucursal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->foto_path)
                                                <img src="{{ asset('storage/' . $user->foto_path) }}" alt="Foto de {{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->name }} {{ $user->apellido }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @foreach ($user->roles as $role)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->sucursal?->nombre ?: 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($user->activo)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @can('editar usuario')
                                            <x-secondary-button wire:click="edit({{ $user->id }})">
                                                {{ __('Editar') }}
                                            </x-secondary-button>
                                            @endcan
                                            @can('eliminar usuario')
                                                @if (auth()->id() !== $user->id) {{-- No mostrar botón de eliminar para el usuario logueado --}}
                                                <x-danger-button wire:click="delete({{ $user->id }})" wire:confirm="¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer." class="ml-2">
                                                    {{ __('Eliminar') }}
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
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">
                                {{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Columna Izquierda: Datos Personales y Foto -->
                                <div>
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                        <input type="text" wire:model.defer="name" id="name" class="mt-1 block w-full input-form">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="apellido" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                                        <input type="text" wire:model.defer="apellido" id="apellido" class="mt-1 block w-full input-form">
                                        @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email" wire:model.defer="email" id="email" class="mt-1 block w-full input-form">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mt-4">
                                        <label for="foto_nueva" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto de Perfil</label>
                                        <input type="file" wire:model="foto_nueva" id="foto_nueva" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        @error('foto_nueva') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                        <div wire:loading wire:target="foto_nueva" class="text-sm text-gray-500 dark:text-gray-400">Cargando...</div>

                                        @if ($foto_nueva)
                                            <img src="{{ $foto_nueva->temporaryUrl() }}" alt="Previsualización" class="mt-2 h-20 w-20 rounded-full object-cover">
                                        @elseif ($foto_path)
                                            <img src="{{ asset('storage/' . $foto_path) }}" alt="Foto actual" class="mt-2 h-20 w-20 rounded-full object-cover">
                                            <button type="button" wire:click="removePhoto" class="mt-1 text-xs text-red-500 hover:text-red-700">Eliminar foto</button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Columna Derecha: Contraseña, Sucursal, Roles, Activo -->
                                <div>
                                    <div class="mt-4 md:mt-0">
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña {{ $userId ? '(Dejar en blanco para no cambiar)' : '' }}</label>
                                        <input type="password" wire:model.defer="password" id="password" class="mt-1 block w-full input-form">
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                                        <input type="password" wire:model.defer="password_confirmation" id="password_confirmation" class="mt-1 block w-full input-form">
                                    </div>

                                    <div class="mt-4">
                                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sucursal Asignada</label>
                                        <select wire:model.defer="sucursal_id" id="sucursal_id" class="mt-1 block w-full input-form">
                                            <option value="">Sin sucursal asignada</option>
                                            @foreach($todasLasSucursales as $id => $nombre)
                                                <option value="{{ $id }}">{{ $nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    @can('asignar roles')
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Roles</label>
                                        <div class="mt-2 grid grid-cols-2 gap-2 max-h-48 overflow-y-auto p-2 border border-gray-300 dark:border-gray-700 rounded-md">
                                            @foreach($todosLosRoles as $roleName => $roleDisplayName)
                                                @php
                                                    $isDisabled = ($roleName === 'Admin' && $userId && \App\Models\User::find($userId)?->hasRole('Admin') && auth()->id() === $userId);
                                                    // Podrías añadir más lógica para deshabilitar la opción Admin si el usuario actual no es Admin
                                                @endphp
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="role_{{ $roleName }}" wire:model.defer="rolesUsuario" type="checkbox" value="{{ $roleName }}"
                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                                               {{ $isDisabled ? 'disabled' : '' }}>
                                                    </div>
                                                    <div class="ml-2 text-sm">
                                                        <label for="role_{{ $roleName }}" class="font-medium text-gray-700 dark:text-gray-300 {{ $isDisabled ? 'text-gray-400 dark:text-gray-500' : '' }}">{{ $roleDisplayName }}</label>
                                                        @if($isDisabled)
                                                            <p class="text-xs text-gray-400 dark:text-gray-500">(No puedes quitarte el rol Admin)</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('rolesUsuario') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    @endcan

                                    <div class="mt-4">
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
    .input-form {
        border-width: 1px;
        border-color: #D1D5DB; /* gray-300 */
        background-color: white;
        color: #374151; /* gray-700 */
        border-radius: 0.375rem; /* rounded-md */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
    }
    .dark .input-form {
        border-color: #4B5563; /* dark:border-gray-700 */
        background-color: #1F2937; /* dark:bg-gray-900 */
        color: #D1D5DB; /* dark:text-gray-300 */
    }
    .input-form:focus {
        border-color: #6366F1; /* focus:border-indigo-500 */
        --tw-ring-color: #6366F1; /* focus:ring-indigo-500 */
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(1px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }
    .dark .input-form:focus {
        border-color: #818CF8; /* dark:focus:border-indigo-600 */
        --tw-ring-color: #818CF8; /* dark:focus:ring-indigo-600 */
    }
</style>
@endpush
