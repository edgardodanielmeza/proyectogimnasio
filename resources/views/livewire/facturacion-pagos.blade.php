<div>
    {{-- Breadcrumbs y Título --}}
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-neutral-700 hover:text-primary">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-neutral-400 mx-1 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ms-1 text-sm font-medium text-neutral-500 md:ms-2">{{ $title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        {{-- El título se toma del layout --}}
    </div>

    {{-- Mensajes Flash --}}
    @if (session()->has('message_pagos'))
        <div class="bg-success-light border-l-4 border-success text-success-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('message_pagos') }}</p>
        </div>
    @endif
    @if (session()->has('error_pagos'))
         <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('error_pagos') }}</p>
        </div>
    @endif

    {{-- Acciones: Búsqueda y Botón de Crear --}}
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
            <input wire:model.debounce.350ms="search" type="text" placeholder="Buscar por miembro, referencia, monto..."
                   class="w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
        </div>
        <button wire:click="crearNuevoPago"
                class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded shadow-md flex items-center w-full sm:w-auto justify-center focus:outline-none focus:ring-2 focus:ring-primary-light">
            {{-- <i class="fas fa-plus-circle mr-2"></i> --}}
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Registrar Nuevo Pago
        </button>
    </div>

    {{-- Tabla de Pagos --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Fecha Pago</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Miembro</th>
                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Monto (€)</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Método</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Referencia</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Membresía Asociada</th>
                    {{-- <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-neutral-600 uppercase tracking-wider">Factura</th> --}}
                    {{-- <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Acciones</th> --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse ($pagos as $pago)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-700">
                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-900 font-medium">
                            {{ $pago->miembro->nombre ?? 'N/A' }} {{ $pago->miembro->apellido ?? '' }}
                            @if($pago->miembro && $pago->miembro->email)
                                <div class="text-xs text-neutral-500">{{ $pago->miembro->email }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-700 text-right">
                            ${{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-700">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $pago->metodo_pago }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500">
                            {{ Str::limit($pago->referencia_pago, 40) ?? 'N/A' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-neutral-500">
                            {{ $pago->membresia->tipoMembresia->nombre ?? 'Pago General' }}
                            @if($pago->membresia)
                                <div class="text-xs">(Inicio: {{ \Carbon\Carbon::parse($pago->membresia->fecha_inicio)->format('d/m/Y') }} - Fin: {{ \Carbon\Carbon::parse($pago->membresia->fecha_fin)->format('d/m/Y') }})</div>
                            @endif
                        </td>
                        {{-- <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-neutral-700">
                            @if($pago->factura_generada)
                                <span class="text-success-dark"><i class="fas fa-check-circle"></i> Sí</span>
                            @else
                                <span class="text-neutral-500"><i class="fas fa-times-circle"></i> No</span>
                            @endif
                        </td> --}}
                        {{-- <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                            <button class="text-primary hover:text-primary-dark" title="Ver Detalles/Factura">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500">
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

    {{-- Paginación --}}
    @if ($pagos->hasPages())
        <div class="mt-4 px-2 py-2">
            {{ $pagos->links() }}
        </div>
    @endif

    {{-- Modal para Registrar Nuevo Pago --}}
    @if($mostrandoModalNuevoPago)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-nuevo-pago" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalNuevoPago"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="guardarPago">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start mb-4">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-light sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-nuevo-pago">Registrar Nuevo Pago</h3>
                                </div>
                            </div>
                            <div class="space-y-4">
                                {{-- Placeholder para el formulario de nuevo pago --}}
                                <p class="text-neutral-600">El formulario detallado para registrar un nuevo pago irá aquí en el siguiente subtask.</p>
                            </div>
                        </div>
                        <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary hover:bg-primary-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                                Guardar Pago
                            </button>
                            <button wire:click="cerrarModalNuevoPago" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
