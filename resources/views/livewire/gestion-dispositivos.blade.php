<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Dispositivos de Control de Acceso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @include('livewire.partials.session-messages')

                    <div class="flex justify-between items-center mb-4">
                        @can('gestionar dispositivos acceso') {{-- O un permiso más específico como 'crear dispositivo' --}}
                        <x-primary-button wire:click="create()">
                            {{ __('Registrar Nuevo Dispositivo') }}
                        </x-primary-button>
                        @endcan
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar dispositivos..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-1/3">
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sucursal</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Dispositivo</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($dispositivos as $dispositivo)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->nombre }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \App\Models\DispositivoControlAcceso::$tiposDispositivo[$dispositivo->tipo_dispositivo] ?? $dispositivo->tipo_dispositivo }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->sucursal->nombre ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->identificador_dispositivo }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                                            <span @class([
                                                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_ACTIVO,
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' => $dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_INACTIVO,
                                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100' => $dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_ERROR,
                                                'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100' => $dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_MANTENIMIENTO,
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100' => !in_array($dispositivo->estado, [\App\Models\DispositivoControlAcceso::ESTADO_ACTIVO, \App\Models\DispositivoControlAcceso::ESTADO_INACTIVO, \App\Models\DispositivoControlAcceso::ESTADO_ERROR, \App\Models\DispositivoControlAcceso::ESTADO_MANTENIMIENTO]),
                                            ])>
                                                {{ \App\Models\DispositivoControlAcceso::$estadosDispositivo[$dispositivo->estado] ?? $dispositivo->estado }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->ip_address ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-center">
                                            @can('gestionar dispositivos acceso') {{-- O 'editar dispositivo' --}}
                                            <x-secondary-button wire:click="edit({{ $dispositivo->id }})" title="Editar Dispositivo">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                            </x-secondary-button>
                                            @endcan
                                            @can('gestionar dispositivos acceso') {{-- O 'eliminar dispositivo' --}}
                                            <x-danger-button wire:click="delete({{ $dispositivo->id }})" wire:confirm="¿Estás seguro de eliminar el dispositivo '{{ $dispositivo->nombre }}'? Esta acción no se puede deshacer." class="ml-2" title="Eliminar Dispositivo">
                                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12.56 0c-.34-.059-.68-.114-1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                            </x-danger-button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No se encontraron dispositivos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $dispositivos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Dispositivo -->
    @if ($isOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title-dispositivo" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" wire:click="closeModal" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-6" id="modal-title-dispositivo">
                                {{ $dispositivoId ? 'Editar Dispositivo' : 'Registrar Nuevo Dispositivo' }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <!-- Columna Izquierda -->
                                <div>
                                    <div>
                                        <label for="nombre_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Dispositivo <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="nombre" id="nombre_dispositivo" class="mt-1 block w-full input-form-ldark">
                                        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="sucursal_id_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sucursal <span class="text-red-500">*</span></label>
                                        <select wire:model.defer="sucursal_id" id="sucursal_id_dispositivo" class="mt-1 block w-full input-form-ldark">
                                            <option value="">Seleccione una sucursal</option>
                                            @foreach($todasLasSucursales as $id => $nombreSucursal)
                                                <option value="{{ $id }}">{{ $nombreSucursal }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="tipo_dispositivo_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Dispositivo <span class="text-red-500">*</span></label>
                                        <select wire:model.live="tipo_dispositivo" id="tipo_dispositivo_select" class="mt-1 block w-full input-form-ldark">
                                            @foreach($todosLosTiposDispositivo as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_dispositivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="identificador_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Identificador Único del Dispositivo <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="identificador_dispositivo" id="identificador_dispositivo" class="mt-1 block w-full input-form-ldark" placeholder="Ej: Serial, MAC, ID interno">
                                        @error('identificador_dispositivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!-- Columna Derecha -->
                                <div>
                                    <div>
                                        <label for="estado_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado <span class="text-red-500">*</span></label>
                                        <select wire:model.defer="estado" id="estado_dispositivo" class="mt-1 block w-full input-form-ldark">
                                            @foreach($todosLosEstadosDispositivo as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="ip_address_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección IP</label>
                                        <input type="text" wire:model.defer="ip_address" id="ip_address_dispositivo" class="mt-1 block w-full input-form-ldark" placeholder="Ej: 192.168.1.100">
                                        @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="mac_address_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección MAC</label>
                                        <input type="text" wire:model.defer="mac_address" id="mac_address_dispositivo" class="mt-1 block w-full input-form-ldark" placeholder="Ej: 00:1A:2B:3C:4D:5E">
                                        @error('mac_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="puerto_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Puerto</label>
                                        <input type="number" wire:model.defer="puerto" id="puerto_dispositivo" class="mt-1 block w-full input-form-ldark" placeholder="Ej: 8080">
                                        @error('puerto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Campos de Configuración Adicional Dinámicos -->
                            @if (!empty($configFieldsDefinition))
                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-3">
                                        Configuración Específica para: <span class="font-semibold">{{ \App\Models\DispositivoControlAcceso::$tiposDispositivo[$tipo_dispositivo] ?? 'este tipo' }}</span>
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                        @foreach ($configFieldsDefinition as $field)
                                            <div>
                                                <label for="config_{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field['label'] }}</label>
                                                @if ($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'url')
                                                    <input type="{{ $field['type'] }}" wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" class="mt-1 block w-full input-form-ldark">
                                                @elseif($field['type'] === 'password')
                                                    <input type="password" wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" class="mt-1 block w-full input-form-ldark">
                                                @elseif ($field['type'] === 'select' && !empty($field['options']))
                                                    <select wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" class="mt-1 block w-full input-form-ldark">
                                                        @foreach ($field['options'] as $optionValue => $optionLabel)
                                                            <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif ($field['type'] === 'checkbox')
                                                    <div class="mt-1">
                                                        <input type="checkbox" wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700">
                                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Habilitar</span>
                                                    </div>
                                                @endif
                                                @error('configuracion_adicional.' . $field['name']) <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="ml-3">
                                {{ $dispositivoId ? 'Actualizar Dispositivo' : 'Guardar Dispositivo' }}
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
    .input-form-ldark { /* Estilo específico para este formulario para evitar conflictos */
        border-width: 1px;
        border-color: #D1D5DB; /* gray-300 */
        background-color: white;
        color: #111827; /* gray-900 */
        border-radius: 0.375rem; /* rounded-md */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
    }
    .dark .input-form-ldark {
        border-color: #4B5563; /* dark:border-gray-600 */
        background-color: #374151; /* dark:bg-gray-700 */
        color: #F3F4F6; /* dark:text-gray-200 */
    }
    .input-form-ldark:focus {
        /* Similar a text-input de Breeze pero sin el anillo exterior completo para mejor estética en modales */
        border-color: #4f46e5; /* indigo-600 */
        box-shadow: 0 0 0 1px #4f46e5; /* Anillo interior */
    }
    .dark .input-form-ldark:focus {
        border-color: #6366f1; /* dark:indigo-500 */
        box-shadow: 0 0 0 1px #6366f1;
    }
</style>
@endpush
