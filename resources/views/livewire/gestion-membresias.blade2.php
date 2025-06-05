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

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-success-light border-l-4 border-success text-success-dark p-4 mb-4" role="alert">
            <p class="font-bold">Éxito</p>
            <p>{{ session('message') }}</p>
        </div>
    @endif

    {{-- Acciones Principales y Filtros --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-2">
        <button wire:click="mostrarModalRegistroMiembro()" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded whitespace-nowrap shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light">
            Registrar Nuevo Miembro
        </button>
        <div class="flex flex-wrap gap-2 items-center">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Buscar por nombre, email..." class="px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:w-auto w-full">
            {{-- Más filtros pueden ir aquí --}}
        </div>
    </div>

    {{-- Modal para Registro/Edición de Miembro --}}
    @if($mostrandoModalRegistro)
    <div class="fixed inset-0 bg-neutral-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center" id="modal-registro-miembro">
        <div class="relative mx-auto p-6 border w-full max-w-2xl shadow-xl rounded-lg bg-white">
            <div class="flex justify-between items-center pb-3">
                <p class="text-2xl font-bold text-neutral-800">{{ $miembroSeleccionadoId ? 'Editar Miembro' : 'Registrar Nuevo Miembro' }}</p>
                <button wire:click="ocultarModalRegistroMiembro()" class="text-neutral-500 hover:text-neutral-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form wire:submit.prevent="guardarMiembro" class="space-y-4">
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
                            @foreach($sucursales as $sucursal)
                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sucursal_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="my-4 border-neutral-200">
                <p class="text-lg font-semibold text-neutral-800">Membresía Inicial</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_membresia_id" class="block text-sm font-medium text-neutral-700">Tipo de Membresía</label>
                        <select wire:model.defer="tipo_membresia_id" id="tipo_membresia_id" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                            <option value="">Seleccione tipo</option>
                            @foreach($tiposMembresia as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }} ({{ $tipo->duracion_dias }} días - ${{ number_format($tipo->precio, 0) }})</option>
                            @endforeach
                        </select>
                        @error('tipo_membresia_id') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="fecha_inicio_membresia" class="block text-sm font-medium text-neutral-700">Fecha Inicio Membresía</label>
                        <input wire:model.defer="fecha_inicio_membresia" type="date" id="fecha_inicio_membresia" class="mt-1 block w-full border-neutral-300 rounded-md shadow-sm focus:border-primary focus:ring-primary sm:text-sm">
                        @error('fecha_inicio_membresia') <span class="text-danger text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" wire:click="ocultarModalRegistroMiembro()" class="px-4 py-2 bg-neutral-200 text-neutral-800 rounded-md hover:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-neutral-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light">
                        {{ $miembroSeleccionadoId ? 'Actualizar Miembro' : 'Guardar Miembro' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Tabla de Miembros/Membresías --}}
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg mt-6">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Sucursal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Membresía Fin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 bg-neutral-100"></th> {{-- Acciones --}}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse ($miembros as $miembro)
                    <tr class="hover:bg-neutral-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-neutral-900">{{ $miembro->nombre }} {{ $miembro->apellido }}</div>
                            <div class="text-sm text-neutral-500">{{ $miembro->telefono ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">{{ $miembro->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">{{ $miembro->sucursal->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                            @if($miembro->latestMembresia)
                                {{ \Carbon\Carbon::parse($miembro->latestMembresia->fecha_fin)->format('d/m/Y') }}
                                <div class="text-xs text-neutral-500">({{ $miembro->latestMembresia->tipoMembresia->nombre ?? 'N/A' }})</div>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($miembro->latestMembresia)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $miembro->latestMembresia->estado == 'activa' && \Carbon\Carbon::parse($miembro->latestMembresia->fecha_fin)->isFuture() ? 'bg-success-light text-success-dark' : '' }}
                                    {{ ($miembro->latestMembresia->estado == 'vencida' || \Carbon\Carbon::parse($miembro->latestMembresia->fecha_fin)->isPast()) && $miembro->latestMembresia->estado != 'cancelada' ? 'bg-danger-light text-danger-dark' : '' }}
                                    {{ $miembro->latestMembresia->estado == 'cancelada' ? 'bg-neutral-200 text-neutral-800' : '' }}
                                    {{ $miembro->latestMembresia->estado == 'suspendida' ? 'bg-warning-light text-warning-dark' : '' }}">

                                    @if(\Carbon\Carbon::parse($miembro->latestMembresia->fecha_fin)->isPast() && $miembro->latestMembresia->estado != 'cancelada' && $miembro->latestMembresia->estado != 'suspendida')
                                        Vencida
                                    @else
                                        {{ ucfirst($miembro->latestMembresia->estado) }}
                                    @endif
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-neutral-200 text-neutral-800">
                                    Sin membresía
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="text-primary hover:text-primary-dark font-medium">Editar</button>
                            {{-- Otros botones de acción aquí --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-neutral-500">
                            No hay miembros registrados que coincidan con la búsqueda.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($miembros->hasPages())
    <div class="mt-4 px-2">
        {{ $miembros->links() }} {{-- Tailwind pagination styles should apply from AppServiceProvider or Livewire config --}}
    </div>
    @endif
</div>
