<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title ?? __('Gestión de Sucursales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @include('livewire.partials.session-messages')

                    <div class="flex justify-between items-center mb-4">
                        @can('crear sucursal')
                        <x-primary-button wire:click="crearNuevaSucursal">
                            {{ __('Crear Nueva Sucursal') }}
                        </x-primary-button>
                        @endcan
                        {{-- Puedes añadir un input de búsqueda aquí si lo deseas en el futuro --}}
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Dirección
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Teléfono
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Horario de Atención
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($sucursales_list as $sucursal) {{-- Cambiado de $sucursales a $sucursales_list --}}
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sucursal->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sucursal->direccion }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sucursal->telefono ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sucursal->horario_atencion ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                            @can('editar sucursal')
                                            <x-secondary-button wire:click="editarSucursal({{ $sucursal->id }})" title="Editar Sucursal">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                            </x-secondary-button>
                                            @endcan
                                            @can('eliminar sucursal')
                                            <x-danger-button wire:click="confirmarEliminacionSucursal({{ $sucursal->id }})" wire:confirm="¿Estás seguro de eliminar la sucursal '{{ $sucursal->nombre }}'? Esta acción no se puede deshacer." class="ml-2" title="Eliminar Sucursal">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c-.34-.059-.68-.114-1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            </x-danger-button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            No hay sucursales registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sucursales_list->links() }}  {{-- Cambiado de $sucursales a $sucursales_list --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Sucursal -->
    @if($mostrandoModalSucursal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title-sucursal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" wire:click="cerrarModalSucursal" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="{{ $modoEdicionSucursal ? 'actualizarSucursal' : 'guardarSucursal' }}">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title-sucursal">
                            {{ $modoEdicionSucursal ? 'Editar Sucursal' : 'Crear Nueva Sucursal' }}
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="sucursal_nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="nombre" id="sucursal_nombre" class="mt-1 block w-full input-form-ldark">
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="sucursal_direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección <span class="text-red-500">*</span></label>
                                <textarea wire:model.defer="direccion" id="sucursal_direccion" rows="3" class="mt-1 block w-full input-form-ldark"></textarea>
                                @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="sucursal_telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                                <input type="tel" wire:model.defer="telefono" id="sucursal_telefono" class="mt-1 block w-full input-form-ldark">
                                @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="sucursal_horario_atencion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Horario de Atención</label>
                                <input type="text" wire:model.defer="horario_atencion" id="sucursal_horario_atencion" class="mt-1 block w-full input-form-ldark" placeholder="Ej: L-V 08:00-22:00, S 09:00-14:00">
                                @error('horario_atencion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit" class="ml-3">
                            {{ $modoEdicionSucursal ? 'Actualizar Sucursal' : 'Guardar Sucursal' }}
                        </x-primary-button>
                        <x-secondary-button type="button" wire:click="cerrarModalSucursal">
                            {{ __('Cancelar') }}
                        </x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal de Confirmación de Eliminación --}}
    @if($mostrandoModalConfirmacionEliminarSucursal)
    <div class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title-delete-sucursal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" wire:click="ocultarModalConfirmacionEliminarSucursal" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title-delete-sucursal">
                                Eliminar Sucursal
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    ¿Estás seguro de que deseas eliminar esta sucursal? Esta acción no se puede deshacer.
                                    Asegúrate de que no tenga datos asociados (miembros, dispositivos, etc.) antes de eliminar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <x-danger-button wire:click="eliminarSucursal()" class="ml-3">
                        Sí, Eliminar
                    </x-danger-button>
                    <x-secondary-button wire:click="ocultarModalConfirmacionEliminarSucursal()">
                        Cancelar
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
