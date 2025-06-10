<div>
    {{-- Breadcrumbs y Título (igual que antes) --}}
    <div class="mb-6">
        <nav class="flex mb-2" aria-label="Breadcrumb">
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
    </div>

    {{-- Mensajes Flash Globales del Componente (para errores de sucursal, etc.) --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
         <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Formulario de Búsqueda de Miembro y QR --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
            {{-- Columna para Búsqueda por Término --}}
            <div>
                <label for="terminoBusqueda" class="block text-sm font-medium text-neutral-700 mb-1">Buscar Miembro</label>
                <div class="flex">
                    <input wire:model.defer="terminoBusqueda" type="text" id="terminoBusqueda"
                           class="flex-grow px-4 py-2 border border-neutral-300 rounded-l-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                           placeholder="Nombre, apellido, email o código...">
                    <button wire:click="buscarMiembroParaAcceso" type="button"
                            class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-3 rounded-r-md shadow-md flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-primary-light">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
                @error('terminoBusqueda') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Columna para Ingreso de Código QR --}}
            <div>
                <label for="codigoQrIngresado" class="block text-sm font-medium text-neutral-700 mb-1">o Ingresar Código QR</label>
                <input wire:model.defer="codigoQrIngresado" type="text" id="codigoQrIngresado"
                       class="w-full px-4 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       placeholder="Pegar código QR aquí...">
                @error('codigoQrIngresado') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
                <p class="text-xs text-neutral-500 mt-1">Si usa un código QR, no necesita buscar al miembro.</p>
            </div>
        </div>
    </div>

    {{-- Mensajes Flash Específicos de Acceso (resultado de búsqueda/registro) --}}
    @if (session()->has('message_acceso'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('message_acceso') }}</p>
        </div>
    @endif
    @if (session()->has('error_acceso'))
         <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('error_acceso') }}</p>
        </div>
    @endif

    {{-- Detalles del Miembro Encontrado y Selección de Dispositivo --}}
    @if($miembroEncontrado)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            {{-- Detalles del Miembro (similar a antes) --}}
            <div class="flex flex-col sm:flex-row items-center mb-6 pb-6 border-b border-neutral-200">
                @if ($miembroEncontrado->foto_path)
                    <img class="h-20 w-20 sm:h-25 sm:w-25 rounded-full object-cover mr-0 sm:mr-6 mb-4 sm:mb-0 shadow-md" src="{{ asset('storage/' . $miembroEncontrado->foto_path) }}" alt="Foto de {{ $miembroEncontrado->nombre }}">
                @else
                    <span class="inline-flex items-center justify-center h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-neutral-300 mr-0 sm:mr-6 mb-4 sm:mb-0 shadow-md">
                        <span class="text-3xl sm:text-4xl font-medium leading-none text-neutral-700">{{ strtoupper(substr($miembroEncontrado->nombre, 0, 1) . substr($miembroEncontrado->apellido, 0, 1)) }}</span>
                    </span>
                @endif
                <div class="text-center sm:text-left">
                    <h3 class="text-2xl font-semibold text-neutral-800">{{ $miembroEncontrado->nombre }} {{ $miembroEncontrado->apellido }}</h3>
                    <p class="text-sm text-neutral-600">{{ $miembroEncontrado->email }}</p>
                    <p class="text-sm text-neutral-500">Código: {{ $miembroEncontrado->codigo_acceso_numerico ?? 'N/A' }}</p>
                     @php $membresiaActiva = $miembroEncontrado->membresiaActivaActual; @endphp
                     @if ($membresiaActiva)
                        <p class="text-sm text-neutral-500 mt-1">
                            Membresía: <span class="font-semibold">{{ $membresiaActiva->tipoMembresia->nombre ?? 'N/D' }}</span>
                            (Estado: <span class="font-semibold">{{ $membresiaActiva->estado }}</span>)
                        </p>
                        <p class="text-sm text-neutral-500">
                            Válida hasta: <span class="font-semibold">{{ \Carbon\Carbon::parse($membresiaActiva->fecha_fin)->format('d/m/Y') }}</span>
                        </p>
                        <p class="text-sm text-neutral-500">
                            Acceso habilitado: <span class="font-semibold">{{ $miembroEncontrado->acceso_habilitado ? 'Sí' : 'No' }}</span>
                        </p>
                    @else
                        <p class="text-sm text-red-500 mt-1">Sin membresía activa.</p>
                    @endif
                </div>
            </div>

            {{-- Selección de Dispositivo --}}
            <div class="mb-4">
                <label for="dispositivoSeleccionadoId" class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Dispositivo de Acceso:</label>
                <select wire:model="dispositivoSeleccionadoId" id="dispositivoSeleccionadoId"
                        class="w-full px-4 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    <option value="">-- Seleccione un dispositivo --</option>
                    @forelse($dispositivosSucursalActual as $dispositivo)
                        <option value="{{ $dispositivo->id }}">{{ $dispositivo->nombre }} ({{ $dispositivo->sucursal->nombre ?? 'Sin sucursal asignada' }})</option>
                    @empty
                        <option value="" disabled>No hay dispositivos activos disponibles</option>
                    @endforelse
                </select>
                @error('dispositivoSeleccionadoId') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Botón Registrar Acceso --}}
            <div class="mt-6 text-center">
                <button wire:click="validarYRegistrarAcceso" type="button"
                        class="bg-success hover:bg-success-dark text-white font-bold py-2 px-4 rounded shadow-md flex items-center justify-center w-full sm:w-auto focus:outline-none focus:ring-2 focus:ring-success-light"
                        @if(!$dispositivoSeleccionadoId) disabled @endif>
                    <svg class="inline-block h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Registrar Acceso Manual
                </button>
                @if(!$dispositivoSeleccionadoId)
                <p class="text-xs text-neutral-500 mt-1">Debe seleccionar un dispositivo para registrar el acceso.</p>
                @endif
            </div>
        </div>
    @elseif(strlen($terminoBusqueda) > 0 && !$errors->has('terminoBusqueda') && !session()->has('message_acceso') && !session()->has('error_acceso'))
        {{-- Mostrar si se buscó algo, no hay errores de input, y no hay mensajes de miembro encontrado/no encontrado --}}
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-neutral-600">No se encontraron resultados para "{{ $terminoBusqueda }}" o la búsqueda no se ha procesado.</p>
        </div>
    @endif

</div>
