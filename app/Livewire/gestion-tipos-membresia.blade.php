<div>
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-neutral-500 hover:text-neutral-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-3 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569 9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <span class="text-neutral-700">{{ $title }}</span>
            </li>
        </ol>
    </nav>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-success-light border-l-4 border-success text-success-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-success-dark mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold">Éxito</p>
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <div class="flex">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-danger-dark mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold">Error</p>
                    <p class="text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Botón de Crear Nuevo Tipo --}}
    <div class="flex justify-end items-center mb-6">
        <button wire:click="crearNuevoTipoMembresia" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light">
            Crear Nuevo Tipo de Membresía
        </button>
    </div>

    {{-- Tabla de Tipos de Membresía --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Descripción</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-600 uppercase tracking-wider">Duración (Días)</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Precio</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-neutral-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse ($tiposMembresia as $tipo)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $tipo->nombre }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-700">{{ Str::limit($tipo->descripcion, 70) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="text-sm text-neutral-700">{{ $tipo->duracion_dias }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm text-neutral-700">${{ number_format($tipo->precio, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <button wire:click="editarTipoMembresia({{ $tipo->id }})" class="text-primary hover:text-primary-dark font-semibold">
                                Editar
                            </button>
                            <button wire:click="confirmarEliminacion({{ $tipo->id }})" class="text-danger hover:text-danger-dark font-semibold ml-3">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500">
                            No hay tipos de membresía registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($tiposMembresia->hasPages())
        <div class="mt-4 px-2 py-2">
            {{ $tiposMembresia->links() }}
        </div>
    @endif

    {{-- Modal de creación/edición --}}
    @if($mostrandoModal)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-tipo" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="{{ $modoEdicion ? 'actualizarTipoMembresia' : 'guardarTipoMembresia' }}">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900 mb-4" id="modal-title-tipo">
                                {{ $modoEdicion ? 'Editar Tipo de Membresía' : 'Crear Nuevo Tipo de Membresía' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-neutral-700">Nombre del Tipo</label>
                                    <input type="text" wire:model.defer="nombre" id="nombre"
                                           class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                           placeholder="Ej: Mensual, Anual, VIP">
                                    @error('nombre') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="descripcion" class="block text-sm font-medium text-neutral-700">Descripción (Opcional)</label>
                                    <textarea wire:model.defer="descripcion" id="descripcion" rows="3"
                                              class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                              placeholder="Breve descripción del tipo de membresía"></textarea>
                                    @error('descripcion') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="duracion_dias" class="block text-sm font-medium text-neutral-700">Duración (en días)</label>
                                    <input type="number" wire:model.defer="duracion_dias" id="duracion_dias" min="1"
                                           class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                           placeholder="Ej: 30, 90, 365">
                                    @error('duracion_dias') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="precio" class="block text-sm font-medium text-neutral-700">Precio (€)</label>
                                    <input type="number" wire:model.defer="precio" id="precio" min="0" step="0.01"
                                           class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                                           placeholder="Ej: 29.99">
                                    @error('precio') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary hover:bg-primary-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                                {{ $modoEdicion ? 'Actualizar' : 'Guardar' }}
                            </button>
                            <button wire:click="cerrarModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-light sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de Confirmación de Eliminación de Tipo de Membresía --}}
    @if($mostrandoModalConfirmacionEliminarTipo)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-delete-tipo" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="ocultarModalConfirmacionEliminarTipo"></div>
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
                            <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-delete-tipo">
                                Eliminar Tipo de Membresía
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600">
                                    ¿Estás seguro de que deseas eliminar este tipo de membresía?
                                    Si este tipo está siendo utilizado por membresías de miembros existentes, no se podrá eliminar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="eliminarTipoMembresia()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-danger hover:bg-danger-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-light sm:ml-3 sm:w-auto sm:text-sm">
                        Sí, Eliminar
                    </button>
                    <button wire:click="ocultarModalConfirmacionEliminarTipo()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-light sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
