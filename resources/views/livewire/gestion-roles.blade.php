<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Roles') }}
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
                        @can('crear rol')
                        <x-primary-button wire:click="create()">
                            {{ __('Crear Nuevo Rol') }}
                        </x-primary-button>
                        @endcan
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar roles..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre del Rol
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Guard
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($roles as $rol)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $rol->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $rol->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $rol->guard_name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if ($rol->name !== 'Admin')
                                                @can('editar rol')
                                                <x-secondary-button wire:click="edit({{ $rol->id }})">
                                                    {{ __('Editar') }}
                                                </x-secondary-button>
                                                @endcan
                                                @can('asignar permisos a rol') {{-- O 'editar rol' --}}
                                                <x-primary-button wire:click="openPermissionsModal({{ $rol->id }})" class="ml-2">
                                                    {{ __('Permisos') }}
                                                </x-primary-button>
                                                @endcan
                                                @can('eliminar rol')
                                                <x-danger-button wire:click="delete({{ $rol->id }})" wire:confirm="¿Estás seguro de eliminar este rol? Esta acción no se puede deshacer." class="ml-2">
                                                    {{ __('Eliminar') }}
                                                </x-danger-button>
                                                @endcan
                                            @else
                                                <span class="text-xs text-gray-500 dark:text-gray-400">No editable</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No se encontraron roles.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Rol -->
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start w-full">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                        {{ $rolId ? 'Editar Rol' : 'Crear Nuevo Rol' }}
                                    </h3>
                                    <div class="mt-2 w-full">
                                        <div>
                                            <label for="nombreRol" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Rol</label>
                                            <input type="text" wire:model.defer="nombreRol" id="nombreRol" name="nombreRol"
                                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            @error('nombreRol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="ml-3">
                                {{ $rolId ? 'Actualizar' : 'Guardar' }}
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

    <!-- Modal para Gestionar Permisos del Rol -->
    @if ($isPermissionsModalOpen && $rolActualPermisos)
        <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="permissions-modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="guardarPermisos">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start w-full">
                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="permissions-modal-title">
                                        Gestionar Permisos para el Rol: <span class="font-bold">{{ $nombreRol }}</span>
                                    </h3>
                                    @if ($nombreRol === 'Admin')
                                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-2">El rol Admin siempre tiene todos los permisos. No se pueden modificar aquí.</p>
                                    @else
                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                                            @forelse ($todosLosPermisos as $id => $nombrePermiso)
                                                <div class="flex items-start">
                                                    <div class="flex items-center h-5">
                                                        <input id="permiso_{{ $id }}" wire:model.defer="permisosSeleccionados" type="checkbox" value="{{ $nombrePermiso }}"
                                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                                                    </div>
                                                    <div class="ml-3 text-sm">
                                                        <label for="permiso_{{ $id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $nombrePermiso }}</label>
                                                    </div>
                                                </div>
                                            @empty
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No hay permisos definidos en el sistema.</p>
                                            @endforelse
                                        </div>
                                        @error('permisosSeleccionados') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            @if ($nombreRol !== 'Admin')
                                <x-primary-button type="submit" class="ml-3">
                                    {{ __('Guardar Permisos') }}
                                </x-primary-button>
                            @endif
                            <x-secondary-button type="button" wire:click="closePermissionsModal()">
                                {{ __('Cancelar') }}
                            </x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
