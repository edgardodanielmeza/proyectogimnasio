<div>
    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8"> {{-- max-w-full para más espacio --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <h2 class="text-2xl font-semibold mb-6">Informe de Eventos de Acceso</h2>

                {{-- Sección de Filtros --}}
                <div class="bg-gray-50 p-4 rounded-md mb-6 shadow">
                    <form wire:submit.prevent="aplicarFiltros">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 items-end">
                            <div>
                                <label for="filtroMiembroId" class="block text-sm font-medium text-gray-700">Miembro</label>
                                <select wire:model.defer="filtroMiembroId" id="filtroMiembroId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    @foreach($all_miembros as $miembro)
                                        <option value="{{ $miembro->id }}">{{ $miembro->apellido }}, {{ $miembro->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filtroSucursalId" class="block text-sm font-medium text-gray-700">Sucursal</label>
                                <select wire:model.defer="filtroSucursalId" id="filtroSucursalId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todas</option>
                                    @foreach($all_sucursales as $sucursal)
                                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filtroFechaDesde" class="block text-sm font-medium text-gray-700">Fecha Desde</label>
                                <input wire:model.defer="filtroFechaDesde" type="date" id="filtroFechaDesde" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="filtroFechaHasta" class="block text-sm font-medium text-gray-700">Fecha Hasta</label>
                                <input wire:model.defer="filtroFechaHasta" type="date" id="filtroFechaHasta" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="filtroResultado" class="block text-sm font-medium text-gray-700">Resultado</label>
                                <select wire:model.defer="filtroResultado" id="filtroResultado" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    @foreach($resultadosDisponibles as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="button" wire:click="limpiarFiltros" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Limpiar Filtros
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Aplicar Filtros
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabla de Eventos de Acceso --}}
                <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Miembro</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sucursal</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Evento</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método Acceso</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resultado</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($eventos_list as $evento)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($evento->fecha_hora)->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        @if($evento->miembro)
                                            {{ $evento->miembro->apellido }}, {{ $evento->miembro->nombre }}
                                        @else
                                            N/A (Miembro no registrado o eliminado)
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $evento->sucursal->nombre ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $evento->dispositivoControlAcceso->nombre ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $tiposEventoDisponibles[$evento->tipo_evento] ?? $evento->tipo_evento }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">{{ $metodosAccesoDisponibles[$evento->metodo_acceso_utilizado] ?? $evento->metodo_acceso_utilizado }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $evento->resultado == 'permitido' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $resultadosDisponibles[$evento->resultado] ?? ucfirst($evento->resultado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-normal text-sm text-gray-700 max-w-xs break-words">{{ $evento->notas }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-sm text-gray-500">
                                        No hay eventos de acceso que coincidan con los filtros aplicados. Pruebe con otros filtros o límpielos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($eventos_list->hasPages())
                <div class="mt-6">
                    {{ $eventos_list->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
