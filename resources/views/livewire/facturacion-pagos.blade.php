<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @include('livewire.partials.session-messages')

                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-2">
                        <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por miembro, ref., método, monto..."
                                   class="w-full input-form-ldark">
                        </div>
                        @can('registrar pago')
                        <x-primary-button wire:click="abrirModalNuevoPago" class="w-full sm:w-auto justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Registrar Nuevo Pago
                        </x-primary-button>
                        @endcan
                    </div>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Miembro</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Monto</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Método</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Referencia</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Membresía Asociada</th>
                                    {{-- <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Factura</th> --}}
                                    {{-- <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th> --}}
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($pagos as $pago)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium">
                                            {{ $pago->miembro->nombre ?? 'N/A' }} {{ $pago->miembro->apellido ?? '' }}
                                            @if($pago->miembro && $pago->miembro->email)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $pago->miembro->email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                                            ${{ number_format($pago->monto, 2) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-sky-100 text-sky-800 dark:bg-sky-700 dark:text-sky-100">
                                                {{ $metodosDePagoDisponibles[$pago->metodo_pago] ?? ucfirst(str_replace('_', ' ', $pago->metodo_pago)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($pago->referencia_pago, 30) ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($pago->membresia)
                                                {{ $pago->membresia->tipoMembresia->nombre ?? 'Membresía General' }}
                                                <div class="text-xs">(Fin: {{ \Carbon\Carbon::parse($pago->membresia->fecha_fin)->format('d/m/Y') }})</div>
                                            @else
                                                Pago General
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                            @if(empty($search))
                                                No hay pagos registrados.
                                            @else
                                                No hay pagos que coincidan con la búsqueda "{{ $search }}".
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($pagos->hasPages())
                        <div class="mt-4 px-2 py-2">
                            {{ $pagos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Registrar Nuevo Pago -->
    @if($mostrandoModalNuevoPago)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-nuevo-pago" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" wire:click="cerrarModalNuevoPago" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="guardarNuevoPago">
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start mb-4">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title-nuevo-pago">Registrar Nuevo Pago</h3>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label for="nuevoPago_miembro_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Miembro <span class="text-red-500">*</span></label>
                                    <select wire:model.live="nuevoPago_miembro_id" id="nuevoPago_miembro_id" class="mt-1 block w-full input-form-ldark">
                                        <option value="">Seleccione un miembro...</option>
                                        @foreach($listaMiembros as $miembro)
                                            <option value="{{ $miembro->id }}">{{ $miembro->apellido }}, {{ $miembro->nombre }} ({{ $miembro->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('nuevoPago_miembro_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                @if(count($listaMembresiasDelMiembro) > 0)
                                <div>
                                    <label for="nuevoPago_membresia_id_opcional" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Asociar a Membresía (Opcional)</label>
                                    <select wire:model.defer="nuevoPago_membresia_id_opcional" id="nuevoPago_membresia_id_opcional" class="mt-1 block w-full input-form-ldark">
                                        <option value="">No asociar / Pago general...</option>
                                        @foreach($listaMembresiasDelMiembro as $membresia)
                                            <option value="{{ $membresia->id }}">
                                                {{ $membresia->tipoMembresia->nombre }} (Fin: {{ \Carbon\Carbon::parse($membresia->fecha_fin)->format('d/m/Y') }}) - Estado: {{ ucfirst($membresia->estado) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nuevoPago_membresia_id_opcional') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                @elseif($nuevoPago_miembro_id)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Este miembro no tiene membresías registradas.</p>
                                @endif

                                <div>
                                    <label for="nuevoPago_monto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model.defer="nuevoPago_monto" id="nuevoPago_monto" step="0.01" class="mt-1 block w-full input-form-ldark" placeholder="Ej: 50.00">
                                    @error('nuevoPago_monto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="nuevoPago_fecha_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Pago <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model.defer="nuevoPago_fecha_pago" id="nuevoPago_fecha_pago" class="mt-1 block w-full input-form-ldark">
                                    @error('nuevoPago_fecha_pago') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="nuevoPago_metodo_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Método de Pago <span class="text-red-500">*</span></label>
                                    <select wire:model.defer="nuevoPago_metodo_pago" id="nuevoPago_metodo_pago" class="mt-1 block w-full input-form-ldark">
                                        @foreach($metodosDePagoDisponibles as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('nuevoPago_metodo_pago') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="nuevoPago_referencia_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Referencia / Notas</label>
                                    <input type="text" wire:model.defer="nuevoPago_referencia_pago" id="nuevoPago_referencia_pago" class="mt-1 block w-full input-form-ldark" placeholder="Ej: ID Transacción, concepto...">
                                    @error('nuevoPago_referencia_pago') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <x-primary-button type="submit" class="ml-3">
                                Guardar Pago
                            </x-primary-button>
                            <x-secondary-button type="button" wire:click="cerrarModalNuevoPago">
                                Cancelar
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
        border-color: #4f46e5; /* indigo-600 */
        box-shadow: 0 0 0 1px #4f46e5; /* Anillo interior */
    }
    .dark .input-form-ldark:focus {
        border-color: #6366f1; /* dark:indigo-500 */
        background-color: #4B5563;
        box-shadow: 0 0 0 1px #6366f1;
    }
</style>
@endpush
