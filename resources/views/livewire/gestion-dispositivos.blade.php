<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-semibold">Gestión de Dispositivos de Acceso</h2>
                    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Registrar Nuevo Dispositivo
                    </button>
                </div>

                <input wire:model.live="searchTerm" type="text" placeholder="Buscar dispositivos por nombre, IP, MAC..." class="form-input rounded-md shadow-sm mt-1 block w-full mb-4"/>

                @if(session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2">Sucursal</th>
                            <th class="px-4 py-2">IP</th>
                            <th class="px-4 py-2">MAC</th>
                            <th class="px-4 py-2">Estado</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dispositivos_list as $dispositivo)
                        <tr>
                            <td class="border px-4 py-2">{{ $dispositivo->nombre }}</td>
                            <td class="border px-4 py-2">{{ $tiposDisponibles[$dispositivo->tipo] ?? $dispositivo->tipo }}</td>
                            <td class="border px-4 py-2">{{ $dispositivo->sucursal->nombre ?? 'N/A' }}</td>
                            <td class="border px-4 py-2">{{ $dispositivo->ip_address }}</td>
                            <td class="border px-4 py-2">{{ $dispositivo->mac_address }}</td>
                            <td class="border px-4 py-2">{{ $estadosDisponibles[$dispositivo->estado] ?? $dispositivo->estado }}</td>
                            <td class="border px-4 py-2">
                                <button wire:click="edit({{ $dispositivo->id }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Editar</button>
                                <button wire:click="delete({{ $dispositivo->id }})" onclick="confirm('¿Está seguro de eliminar este dispositivo?') || event.stopImmediatePropagation()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="border px-4 py-2 text-center">No se encontraron dispositivos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $dispositivos_list->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Dispositivo -->
    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" style="display: @if($isOpen) block @else none @endif;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <form>
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                            {{ $dispositivo_id ? 'Editar Dispositivo' : 'Registrar Nuevo Dispositivo' }}
                        </h3>
                        <div class="mt-2">
                            <div class="mb-4">
                                <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nombre" wire:model.defer="nombre">
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="tipo" class="block text-gray-700 text-sm font-bold mb-2">Tipo:</label>
                                <select id="tipo" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model.defer="tipo">
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tiposDisponibles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('tipo') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="sucursal_id" class="block text-gray-700 text-sm font-bold mb-2">Sucursal:</label>
                                <select id="sucursal_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model.defer="sucursal_id">
                                    <option value="">Seleccione una sucursal</option>
                                    @foreach($all_sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('sucursal_id') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="ip_address" class="block text-gray-700 text-sm font-bold mb-2">Dirección IP:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="ip_address" wire:model.defer="ip_address">
                                @error('ip_address') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="mac_address" class="block text-gray-700 text-sm font-bold mb-2">Dirección MAC:</label>
                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="mac_address" wire:model.defer="mac_address">
                                @error('mac_address') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>

                            <div class="mb-4">
                                <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                                <select id="estado" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" wire:model.defer="estado">
                                    <option value="">Seleccione un estado</option>
                                    @foreach($estadosDisponibles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('estado') <span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                            <button wire:click.prevent="store()" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Guardar
                            </button>
                        </span>
                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                            <button wire:click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                Cancelar
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
