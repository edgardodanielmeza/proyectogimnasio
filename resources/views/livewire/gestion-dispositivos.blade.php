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
                        @can('gestionar dispositivos acceso') {{-- O 'crear dispositivo' --}}
                        <x-primary-button wire:click="create()">
                            {{ __('Registrar Nuevo Dispositivo') }}
                        </x-primary-button>
                        @endcan
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar dispositivos..." class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-1/3">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sucursal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID Dispositivo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">IP</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($dispositivos as $dispositivo)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \App\Models\DispositivoControlAcceso::$tiposDispositivo[$dispositivo->tipo_dispositivo] ?? $dispositivo->tipo_dispositivo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->sucursal->nombre ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->identificador_dispositivo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_ACTIVO) bg-green-100 text-green-800 @endif
                                                @if($dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_INACTIVO) bg-yellow-100 text-yellow-800 @endif
                                                @if($dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_ERROR) bg-red-100 text-red-800 @endif
                                                @if($dispositivo->estado == \App\Models\DispositivoControlAcceso::ESTADO_MANTENIMIENTO) bg-blue-100 text-blue-800 @endif
                                            ">
                                                {{ \App\Models\DispositivoControlAcceso::$estadosDispositivo[$dispositivo->estado] ?? $dispositivo->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $dispositivo->ip_address ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @can('gestionar dispositivos acceso') {{-- O 'editar dispositivo' --}}
                                            <x-secondary-button wire:click="edit({{ $dispositivo->id }})">
                                                {{ __('Editar') }}
                                            </x-secondary-button>
                                            @endcan
                                            @can('gestionar dispositivos acceso') {{-- O 'eliminar dispositivo' --}}
                                            <x-danger-button wire:click="delete({{ $dispositivo->id }})" wire:confirm="¿Estás seguro de eliminar este dispositivo? Esta acción no se puede deshacer." class="ml-2">
                                                {{ __('Eliminar') }}
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
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="store">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4" id="modal-title">
                                {{ $dispositivoId ? 'Editar Dispositivo' : 'Registrar Nuevo Dispositivo' }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Columna Izquierda -->
                                <div>
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Dispositivo <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="nombre" id="nombre" class="mt-1 block w-full input-form">
                                        @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="sucursal_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sucursal <span class="text-red-500">*</span></label>
                                        <select wire:model.defer="sucursal_id" id="sucursal_id" class="mt-1 block w-full input-form">
                                            <option value="">Seleccione una sucursal</option>
                                            @foreach($todasLasSucursales as $id => $nombreSucursal)
                                                <option value="{{ $id }}">{{ $nombreSucursal }}</option>
                                            @endforeach
                                        </select>
                                        @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="tipo_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Dispositivo <span class="text-red-500">*</span></label>
                                        <select wire:model.live="tipo_dispositivo" id="tipo_dispositivo" class="mt-1 block w-full input-form">
                                            @foreach($todosLosTiposDispositivo as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('tipo_dispositivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="identificador_dispositivo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Identificador Único del Dispositivo <span class="text-red-500">*</span></label>
                                        <input type="text" wire:model.defer="identificador_dispositivo" id="identificador_dispositivo" class="mt-1 block w-full input-form" placeholder="Ej: Serial, MAC, ID interno">
                                        @error('identificador_dispositivo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!-- Columna Derecha -->
                                <div>
                                    <div>
                                        <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado <span class="text-red-500">*</span></label>
                                        <select wire:model.defer="estado" id="estado" class="mt-1 block w-full input-form">
                                            @foreach($todosLosEstadosDispositivo as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="ip_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección IP</label>
                                        <input type="text" wire:model.defer="ip_address" id="ip_address" class="mt-1 block w-full input-form" placeholder="Ej: 192.168.1.100">
                                        @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="mac_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección MAC</label>
                                        <input type="text" wire:model.defer="mac_address" id="mac_address" class="mt-1 block w-full input-form" placeholder="Ej: 00:1A:2B:3C:4D:5E">
                                        @error('mac_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="puerto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Puerto</label>
                                        <input type="number" wire:model.defer="puerto" id="puerto" class="mt-1 block w-full input-form" placeholder="Ej: 8080">
                                        @error('puerto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Campos de Configuración Adicional Dinámicos -->
                            @if (!empty($configFields))
                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Configuración Específica para {{ \App\Models\DispositivoControlAcceso::$tiposDispositivo[$tipo_dispositivo] ?? 'este tipo' }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @foreach ($configFields as $field)
                                            <div>
                                                <label for="config_{{ $field['name'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field['label'] }}</label>
                                                @if ($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'password')
                                                    <input type="{{ $field['type'] }}" wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" value="{{ $field['value'] ?? '' }}" class="mt-1 block w-full input-form">
                                                @elseif ($field['type'] === 'select' && !empty($field['options']))
                                                    <select wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" class="mt-1 block w-full input-form">
                                                        @foreach ($field['options'] as $optionValue => $optionLabel)
                                                            <option value="{{ $optionValue }}" {{ (isset($field['value']) && $field['value'] == $optionValue) ? 'selected' : '' }}>{{ $optionLabel }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif ($field['type'] === 'checkbox')
                                                    <input type="checkbox" wire:model.defer="configuracion_adicional.{{ $field['name'] }}" id="config_{{ $field['name'] }}" {{ (isset($field['value']) && $field['value']) ? 'checked' : '' }} class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-700">
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

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // Puedes añadir JS específico aquí si es necesario, por ejemplo, para inicializar
        // selectores enriquecidos si los usas para `configuracion_adicional` o tipos.
    });
</script>
@endpush
