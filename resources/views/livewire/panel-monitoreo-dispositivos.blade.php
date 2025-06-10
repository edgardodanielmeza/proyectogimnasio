<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-2xl font-semibold mb-4">Panel de Monitoreo de Dispositivos</h2>

                <div class="mb-4">
                    <label for="filtroSucursalId" class="block text-sm font-medium text-gray-700">Filtrar por Sucursal:</label>
                    <select wire:model.live="filtroSucursalId" id="filtroSucursalId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Todas las Sucursales</option>
                        @foreach($all_sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($dispositivos_list as $dispositivo)
                        <div class="bg-white rounded-lg shadow p-4 border
                            @switch($dispositivo->estado)
                                @case('activo') border-green-500 @break
                                @case('inactivo') border-red-500 @break
                                @case('mantenimiento') border-yellow-500 @break
                                @default border-gray-300
                            @endswitch
                        ">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold">{{ $dispositivo->nombre }}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @switch($dispositivo->estado)
                                        @case('activo') bg-green-100 text-green-800 @break
                                        @case('inactivo') bg-red-100 text-red-800 @break
                                        @case('mantenimiento') bg-yellow-100 text-yellow-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ $estados_mapping[$dispositivo->estado] ?? $dispositivo->estado }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Sucursal:</span> {{ $dispositivo->sucursal->nombre ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Tipo:</span> {{ $tipos_mapping[$dispositivo->tipo] ?? $dispositivo->tipo }}
                            </p>
                            @if($dispositivo->ip_address)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">IP:</span> {{ $dispositivo->ip_address }}
                            </p>
                            @endif
                            @if($dispositivo->mac_address)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">MAC:</span> {{ $dispositivo->mac_address }}
                            </p>
                            @endif
                            @if($dispositivo->ultimo_heartbeat_at)
                            <p class="text-sm text-gray-500 mt-2">
                                <span class="font-medium">Último Heartbeat:</span> {{ \Carbon\Carbon::parse($dispositivo->ultimo_heartbeat_at)->diffForHumans() }}
                            </p>
                            @else
                            <p class="text-sm text-gray-500 mt-2">
                                <span class="font-medium">Último Heartbeat:</span> N/A
                            </p>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-full">No se encontraron dispositivos con los filtros seleccionados.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
