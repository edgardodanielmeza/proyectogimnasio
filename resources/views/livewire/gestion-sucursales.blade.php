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
                        <span class="ms-1 text-sm font-medium text-neutral-500 md:ms-2">{{ $title ?? 'Gestión de Sucursales' }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        {{-- El título se toma del layout principal a través de la propiedad $title del componente --}}
    </div>

    {{-- Mensajes Flash --}}
    @if (session()->has('message'))
        <div class="bg-success-light border-l-4 border-success text-success-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
     @if (session()->has('error'))
        <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Acciones: Búsqueda y Botón de Crear --}}
    <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-2">
        <div class="w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
            <input wire:model.debounce.350ms="searchSucursales" type="text"
                   placeholder="Buscar sucursal por nombre o dirección..."
                   class="w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
        </div>
        <button wire:click="crearNuevaSucursal"
                class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded shadow-md flex items-center w-full sm:w-auto justify-center focus:outline-none focus:ring-2 focus:ring-primary-light">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Crear Nueva Sucursal
        </button>
    </div>

    {{-- Tabla de Sucursales --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                        Dirección
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">
                        Teléfono
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider min-w-[120px]">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse ($sucursales as $sucursal)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $sucursal->nombre }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ Str::limit($sucursal->direccion, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ $sucursal->telefono ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="editarSucursal({{ $sucursal->id }})" class="text-primary hover:text-primary-dark" title="Editar">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="confirmarEliminacionSucursal({{ $sucursal->id }})" class="text-danger hover:text-danger-dark ml-2" title="Eliminar">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500">
                            @if(empty($searchSucursales))
                                No hay sucursales registradas.
                            @else
                                No hay sucursales que coincidan con la búsqueda "{{ $searchSucursales }}".
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if ($sucursales->hasPages())
        <div class="mt-4 px-2 py-2">
            {{ $sucursales->links() }}
        </div>
    @endif

    {{-- Modal de Creación/Edición de Sucursal --}}
    @if($mostrandoModalSucursal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-sucursal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalSucursal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="{{ $modoEdicionSucursal ? 'actualizarSucursal' : 'guardarSucursal' }}">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start mb-4">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ $modoEdicionSucursal ? 'bg-warning-light' : 'bg-primary-light' }} sm:mx-0 sm:h-10 sm:w-10">
                                @if($modoEdicionSucursal)
                                <svg class="h-6 w-6 text-warning-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                @else
                                <svg class="h-6 w-6 text-primary-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 016-6h6a6 6 0 016 6v1h-3"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-sucursal">
                                    {{ $modoEdicionSucursal ? 'Editar Sucursal' : 'Crear Nueva Sucursal' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                        <div>
                                            <label for="sucursal_nombre" class="block text-sm font-medium text-neutral-700">Nombre de la Sucursal <span class="text-danger">*</span></label>
                                            <input type="text" wire:model.defer="nombre" id="sucursal_nombre"
                                                   class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                                   placeholder="Ej: Sucursal Centro">
                                            @error('nombre') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="sucursal_direccion" class="block text-sm font-medium text-neutral-700">Dirección <span class="text-danger">*</span></label>
                                            <textarea wire:model.defer="direccion" id="sucursal_direccion" rows="3"
                                                      class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                                      placeholder="Calle Falsa 123, Ciudad"></textarea>
                                            @error('direccion') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="sucursal_telefono" class="block text-sm font-medium text-neutral-700">Teléfono</label>
                                            <input type="tel" wire:model.defer="telefono" id="sucursal_telefono"
                                                   class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                                   placeholder="Ej: +34 900 123 456">
                                            @error('telefono') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary hover:bg-primary-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            <span wire:loading wire:target="{{ $modoEdicionSucursal ? 'actualizarSucursal' : 'guardarSucursal' }}" class="mr-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            {{ $modoEdicionSucursal ? 'Actualizar Sucursal' : 'Guardar Sucursal' }}
                        </button>
                        <button wire:click="cerrarModalSucursal" type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal de Confirmación de Eliminación de Sucursal --}}
    @if($mostrandoModalConfirmacionEliminarSucursal)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-delete-sucursal" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="ocultarModalConfirmacionEliminarSucursal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-danger-light sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-danger" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-delete-sucursal">
                                Eliminar Sucursal
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600">
                                    ¿Estás seguro de que deseas eliminar esta sucursal?
                                    Esta acción no se puede deshacer. Si la sucursal tiene miembros, dispositivos u otros datos asociados, no se podrá eliminar y deberás reasignarlos primero.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="eliminarSucursal()" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-danger hover:bg-danger-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger sm:ml-3 sm:w-auto sm:text-sm">
                        Sí, Eliminar
                    </button>
                    <button wire:click="ocultarModalConfirmacionEliminarSucursal()" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
