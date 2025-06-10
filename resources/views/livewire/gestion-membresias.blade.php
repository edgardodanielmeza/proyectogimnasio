<div>
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-neutral-500 hover:text-neutral-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-3 text-neutral-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569 9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <span class="text-neutral-700">Miembros</span>
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

    {{-- Acciones Principales y Filtros --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-2">
        <button wire:click="mostrarModalRegistroMiembro()" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded whitespace-nowrap shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light">
            Registrar Nuevo Miembro
        </button>
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar por nombre, email..." class="px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:w-auto w-full">
        </div>
    </div>

    {{-- Modal para Registro/Edición de Miembro --}}
    @if($mostrandoModalRegistro)
    <div class="fixed inset-0 bg-neutral-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center" id="modal-registro-miembro">
        <div class="relative mx-auto p-6 border w-full max-w-2xl shadow-xl rounded-lg bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl font-bold text-neutral-800">
                    {{ $miembroSeleccionadoId ? 'Editar Miembro' : 'Registrar Nuevo Miembro' }}
                </h3>
                <button wire:click="ocultarModalRegistroMiembro()" class="text-neutral-500 hover:text-neutral-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form wire:submit.prevent="{{ $miembroSeleccionadoId ? 'actualizarMiembro' : 'guardarMiembro' }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-neutral-700">Nombre</label>
                        <input wire:model.defer="nombre" type="text" id="nombre" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('nombre') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="apellido" class="block text-sm font-medium text-neutral-700">Apellido</label>
                        <input wire:model.defer="apellido" type="text" id="apellido" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('apellido') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700">Email</label>
                        <input wire:model.defer="email" type="email" id="email" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('email') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-neutral-700">Teléfono</label>
                        <input wire:model.defer="telefono" type="tel" id="telefono" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('telefono') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-neutral-700">Fecha de Nacimiento</label>
                        <input wire:model.defer="fecha_nacimiento" type="date" id="fecha_nacimiento" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('fecha_nacimiento') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-neutral-700">Dirección</label>
                        <input wire:model.defer="direccion" type="text" id="direccion" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('direccion') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="sucursal_id" class="block text-sm font-medium text-neutral-700">Sucursal</label>
                        <select wire:model.defer="sucursal_id" id="sucursal_id" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione sucursal</option>
                            @if($sucursales)
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('sucursal_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="foto" class="block text-sm font-medium text-neutral-700">Foto del Miembro</label>
                        <input type="file" wire:model="foto" id="foto" class="mt-1 block w-full text-sm text-neutral-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-primary-light file:text-primary-dark
                            hover:file:bg-primary/80 cursor-pointer">
                        <div wire:loading wire:target="foto" class="text-xs text-neutral-500 mt-1">Cargando foto...</div>
                        @error('foto') <span class="text-danger text-xs">{{ $message }}</span> @enderror

                        @if ($foto)
                            <div class="mt-2">
                                <p class="text-xs text-neutral-500">Previsualización nueva foto:</p>
                                <img src="{{ $foto->temporaryUrl() }}" alt="Previsualización" class="mt-1 h-20 w-20 object-cover rounded-md shadow">
                            </div>
                        @elseif ($foto_actual_path)
                            <div class="mt-2">
                                <p class="text-xs text-neutral-500">Foto actual:</p>
                                <img src="{{ asset('storage/' . $foto_actual_path) }}" alt="Foto actual" class="mt-1 h-20 w-20 object-cover rounded-md shadow">
                            </div>
                        @endif
                    </div>
                </div>

                @if(!$miembroSeleccionadoId)
                <hr class="my-4 border-neutral-200">
                <p class="text-lg font-semibold text-neutral-800">Membresía Inicial</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_membresia_id" class="block text-sm font-medium text-neutral-700">Tipo de Membresía</label>
                        <select wire:model.defer="tipo_membresia_id" id="tipo_membresia_id" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione tipo</option>
                             @if($tiposMembresia)
                                @foreach($tiposMembresia as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }} ({{ $tipo->duracion_dias }} días - ${{ number_format($tipo->precio, 0) }})</option>
                                @endforeach
                            @endif
                        </select>
                        @error('tipo_membresia_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="fecha_inicio_membresia" class="block text-sm font-medium text-neutral-700">Fecha Inicio Membresía</label>
                        <input wire:model.defer="fecha_inicio_membresia" type="date" id="fecha_inicio_membresia" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('fecha_inicio_membresia') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="ocultarModalRegistroMiembro()" class="px-4 py-2 bg-neutral-200 text-neutral-800 rounded-md hover:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light">
                        {{ $miembroSeleccionadoId ? 'Actualizar Miembro' : 'Registrar Miembro' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Modal de Confirmación para Cancelar Membresía --}}
    @if($mostrandoModalConfirmacionCancelarMembresia)
    <div class="fixed z-60 inset-0 overflow-y-auto" aria-labelledby="modal-title-cancelar-membresia" role="dialog" aria-modal="true"> {{-- z-60 para estar sobre el modal de gestión (z-50) --}}
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="ocultarModalConfirmacionCancelarMembresia"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-warning-light sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-warning-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-cancelar-membresia">
                                Confirmar Cancelación de Membresía
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600">
                                    ¿Estás seguro de que deseas cancelar la membresía: <strong class="font-semibold text-neutral-700">{{ $membresiaParaCancelarInfo }}</strong>?
                                    La membresía se marcará como 'cancelada'. Su fecha de fin original se mantendrá.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="ejecutarCancelacionMembresia()" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-warning hover:bg-warning-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-warning sm:ml-3 sm:w-auto sm:text-sm">
                        Sí, Cancelar Membresía
                    </button>
                    <button wire:click="ocultarModalConfirmacionCancelarMembresia()" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:w-auto sm:text-sm">
                        No, Mantener Activa
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Tabla de Miembros/Membresías --}}
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg mt-6">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Foto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Sucursal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Membresía Fin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 bg-neutral-100 text-right text-xs font-medium text-neutral-600 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse ($miembros as $miembro)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($miembro->foto_path)
                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $miembro->foto_path) }}" alt="Foto de {{ $miembro->nombre }}">
                            @else
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-neutral-300">
                                    <span class="text-xs font-medium leading-none text-neutral-700">{{ strtoupper(substr($miembro->nombre, 0, 1) . substr($miembro->apellido, 0, 1)) }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $miembro->nombre }} {{ $miembro->apellido }}</div>
                            <div class="text-sm text-neutral-500">{{ $miembro->telefono ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">{{ $miembro->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">{{ $miembro->sucursal->nombre ?? 'N/A' }}</td>
                        @php
                            $membresiaAMostrar = $miembro->membresiaActivaActual ?? $miembro->ultimaMembresiaGeneral;
                        @endphp
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            @if($membresiaAMostrar)
                                {{ \Carbon\Carbon::parse($membresiaAMostrar->fecha_fin)->format('d/m/Y') }}
                                <div class="text-xs text-neutral-500">({{ $membresiaAMostrar->tipoMembresia->nombre ?? 'N/A' }})</div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($membresiaAMostrar)
                                @php
                                    $estadoVisible = $membresiaAMostrar->estado;
                                    $fechaFinVisible = \Carbon\Carbon::parse($membresiaAMostrar->fecha_fin);
                                    $hoyVisible = \Carbon\Carbon::today();
                                    $claseBadgeVisible = 'bg-neutral-200 text-neutral-800'; // Default
                                    $textoEstadoVisible = ucfirst($estadoVisible);

                                    if ($estadoVisible == 'activa' && $fechaFinVisible->gte($hoyVisible)) {
                                        $claseBadgeVisible = 'bg-success-light text-success-dark';
                                    } elseif ($estadoVisible == 'vencida' || ($fechaFinVisible->lt($hoyVisible) && !in_array($estadoVisible, ['cancelada', 'suspendida'])) ) {
                                        $claseBadgeVisible = 'bg-danger-light text-danger-dark';
                                        $textoEstadoVisible = 'Vencida';
                                    } elseif ($estadoVisible == 'suspendida') {
                                        $claseBadgeVisible = 'bg-warning-light text-warning-dark';
                                    } elseif ($estadoVisible == 'cancelada') {
                                        $claseBadgeVisible = 'bg-neutral-400 text-black'; // Un gris más oscuro para cancelada
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $claseBadgeVisible }}">
                                    {{ $textoEstadoVisible }}
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-neutral-200 text-neutral-800">
                                    Sin membresía
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editarMiembro({{ $miembro->id }})" class="text-primary hover:text-primary-dark font-medium" title="Editar Miembro">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="abrirModalGestionMembresias({{ $miembro->id }})" class="text-green-600 hover:text-green-800 font-medium ml-2" title="Gestionar Membresías">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{-- Icono de tarjeta ID o perfil --}}
                            </button>
                            <button wire:click="confirmarEliminacionMiembro({{ $miembro->id }})" class="text-danger hover:text-danger-dark font-medium ml-2" title="Eliminar Miembro">
                                <svg class="inline-block h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500"> {{-- Incremented colspan to 7 --}}
                            No hay miembros registrados que coincidan con la búsqueda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($miembros->hasPages())
    <div class="mt-4 px-2 py-2"> {{-- Added py-2 for consistency --}}
        {{ $miembros->links() }}
    </div>
    @endif

    {{-- Modal de Confirmación de Eliminación --}}
    @if($mostrandoModalConfirmacionEliminar)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-delete" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="ocultarModalConfirmacionEliminar"></div>
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
                            <h3 class="text-lg leading-6 font-medium text-neutral-900" id="modal-title-delete">
                                Eliminar Miembro
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-neutral-600">
                                    ¿Estás seguro de que deseas eliminar a este miembro? Esta acción no se puede deshacer. Los datos asociados podrían ser eliminados también (dependiendo de la configuración de la base de datos).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="eliminarMiembro()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-danger hover:bg-danger-dark text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-light sm:ml-3 sm:w-auto sm:text-sm">
                        Sí, Eliminar
                    </button>
                    <button wire:click="ocultarModalConfirmacionEliminar()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-light sm:mt-0 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal para Gestionar Membresías de un Miembro --}}
    @if($mostrandoModalGestionMembresiasMiembro && $miembroParaGestionarMembresias)
    <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-gestion-membresias" role="dialog" aria-modal="true"> {{-- Increased z-index --}}
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="cerrarModalGestionMembresias"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <div class="sm:flex sm:items-start mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            {{-- Icono para gestión de membresías --}}
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5.5A2.5 2.5 0 0112 3v2.5m0 0V3m0 2.5A2.5 2.5 0 0014.5 8H17M5 10h14M5 14h4m2 0h4m-2-4h.01M12 10h.01M12 6h.01M7 10h.01M7 14h.01"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-medium text-neutral-900" id="modal-title-gestion-membresias">
                                Membresías de: <span class="font-bold text-primary">{{ $miembroParaGestionarMembresias->nombre }} {{ $miembroParaGestionarMembresias->apellido }}</span>
                            </h3>
                        </div>
                    </div>

                    {{-- Sección para el Historial de Membresías --}}
                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-neutral-800 mb-2">Historial de Membresías</h4>
                        <div class="bg-neutral-50 p-3 rounded-md shadow max-h-60 overflow-y-auto">
                            @if (session()->has('info_modal_gestion'))
                                <div class="bg-info-light border-l-4 border-info text-info-dark p-3 mb-3 text-xs rounded" role="alert">
                                    <p>{{ session('info_modal_gestion') }}</p>
                                </div>
                            @endif
                            @if($historialMembresias->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-neutral-200 text-sm">
                                    <thead class="bg-neutral-100">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left font-medium text-neutral-500 uppercase tracking-wider">Tipo</th>
                                            <th scope="col" class="px-4 py-2 text-left font-medium text-neutral-500 uppercase tracking-wider">Inicio</th>
                                            <th scope="col" class="px-4 py-2 text-left font-medium text-neutral-500 uppercase tracking-wider">Fin</th>
                                            <th scope="col" class="px-4 py-2 text-left font-medium text-neutral-500 uppercase tracking-wider">Estado</th>
                                            <th scope="col" class="px-4 py-2 text-left font-medium text-neutral-500 uppercase tracking-wider">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        @foreach ($historialMembresias as $membresia)
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    {{ $membresia->tipoMembresia->nombre ?? 'Tipo Desconocido' }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($membresia->fecha_inicio)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($membresia->fecha_fin)->format('d/m/Y') }}
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap">
                                                    @php
                                                        $estadoMembresiaHistorial = $membresia->estado;
                                                        $fechaFinMembresiaHistorial = \Carbon\Carbon::parse($membresia->fecha_fin);
                                                        $hoyHistorial = \Carbon\Carbon::today();
                                                        $claseBadgeHistorialItem = 'bg-neutral-200 text-neutral-800'; // Default
                                                        $textoEstadoHistorialItem = ucfirst($estadoMembresiaHistorial);

                                                        if ($estadoMembresiaHistorial == 'activa' && $fechaFinMembresiaHistorial->gte($hoyHistorial)) {
                                                            $claseBadgeHistorialItem = 'bg-success-light text-success-dark';
                                                        } elseif ($estadoMembresiaHistorial == 'vencida' || $fechaFinMembresiaHistorial->lt($hoyHistorial)) {
                                                            if ($estadoMembresiaHistorial != 'cancelada' && $estadoMembresiaHistorial != 'suspendida') { // No sobreescribir si ya está cancelada/suspendida
                                                                $claseBadgeHistorialItem = 'bg-danger-light text-danger-dark';
                                                                $textoEstadoHistorialItem = 'Vencida';
                                                            }
                                                        }

                                                        if ($estadoMembresiaHistorial == 'suspendida') {
                                                            $claseBadgeHistorialItem = 'bg-warning-light text-warning-dark';
                                                        } elseif ($estadoMembresiaHistorial == 'cancelada') {
                                                            $claseBadgeHistorialItem = 'bg-neutral-400 text-black';
                                                        }
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $claseBadgeHistorialItem }}">
                                                        {{ $textoEstadoHistorialItem }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-xs">
                                                    @if($estadoMembresiaHistorial == 'activa' && $fechaFinMembresiaHistorial->gte($hoyHistorial))
                                                        <button wire:click="confirmarCancelacionMembresia({{ $membresia->id }})" class="text-red-500 hover:text-red-700" title="Cancelar Membresía">
                                                            <i class="fas fa-times-circle mr-1"></i>Cancelar
                                                        </button>
                                                    @elseif($estadoMembresiaHistorial == 'vencida' || $fechaFinMembresiaHistorial->lt($hoyHistorial) || $estadoMembresiaHistorial == 'cancelada')
                                                        <button wire:click="prepararRenovacionMembresia({{ $membresia->id }})" class="text-blue-500 hover:text-blue-700" title="Renovar Membresía">
                                                            <i class="fas fa-redo mr-1"></i>Renovar
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <p class="text-sm text-neutral-500 text-center py-4">Este miembro no tiene historial de membresías.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Sección para Añadir Nueva Membresía --}}
                    <div class="border-t border-neutral-200 pt-4">
                        <h4 class="text-lg font-medium text-neutral-800 mb-3">Añadir Nueva Membresía</h4>
                        <div class="bg-neutral-50 p-4 rounded-md shadow">
                            @if (session()->has('message_modal_gestion'))
                                <div class="bg-success-light border-l-4 border-success text-success-dark p-3 mb-3 text-sm rounded" role="alert">
                                    <p>{{ session('message_modal_gestion') }}</p>
                                </div>
                            @endif
                            @if (session()->has('error_modal_gestion'))
                                <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-3 mb-3 text-sm rounded" role="alert">
                                    <p>{{ session('error_modal_gestion') }}</p>
                                </div>
                            @endif
                            <form wire:submit.prevent="guardarNuevaMembresiaParaMiembro">
                                <div class="space-y-3">
                                    <div>
                                        <label for="nuevaMembresia_tipo_id" class="block text-sm font-medium text-neutral-700">Tipo de Membresía <span class="text-danger">*</span></label>
                                        <select wire:model.defer="nuevaMembresia_tipo_id" id="nuevaMembresia_tipo_id"
                                                class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                            <option value="">Seleccione un tipo...</option>
                                            @if($tiposMembresia)
                                                @foreach($tiposMembresia as $tipo)
                                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }} ({{ $tipo->duracion_dias }} días - ${{ number_format($tipo->precio, 0) }})</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('nuevaMembresia_tipo_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="nuevaMembresia_fecha_inicio" class="block text-sm font-medium text-neutral-700">Fecha de Inicio <span class="text-danger">*</span></label>
                                        <input type="date" wire:model.defer="nuevaMembresia_fecha_inicio" id="nuevaMembresia_fecha_inicio"
                                               class="mt-1 block w-full border border-neutral-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                                        @error('nuevaMembresia_fecha_inicio') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <button type="submit"
                                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                            <span wire:loading wire:target="guardarNuevaMembresiaParaMiembro" class="mr-2">
                                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </span>
                                            Añadir Nueva Membresía
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="bg-neutral-100 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="cerrarModalGestionMembresias" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
