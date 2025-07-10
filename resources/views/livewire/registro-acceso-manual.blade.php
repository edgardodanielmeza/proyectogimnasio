<div>
    {{-- Breadcrumbs y Título --}}
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
        {{-- El título se toma del layout --}}
    </div>

    {{-- Mensajes Flash --}}
    @if (session()->has('message_acceso'))
        <div class="bg-success-light border-l-4 border-success text-success-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('message_acceso') }}</p>
        </div>
    @endif
    @if (session()->has('error_acceso'))
         <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4 mb-4 rounded-md shadow-sm" role="alert">
            <p>{{ session('error_acceso') }}</p>
        </div>
    @endif

    {{-- Formulario de Búsqueda --}}
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form wire:submit.prevent="buscarMiembro" class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3 rtl:space-x-reverse">
            <div class="flex-grow w-full">
                <label for="terminoBusqueda" class="text-lg font-semibold text-gray-700 dark:text-gray-400">Registro de Ingreso</label>
                <input wire:model.lazy="terminoBusqueda" type="text" id="terminoBusqueda"
                       class="w-full px-4 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                       placeholder="Buscar por nombre, apellido, email o código...">
                @error('terminoBusqueda') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                {{-- <i class="fas fa-search mr-2"></i> --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Buscar
            </button>
        </form>
    </div>

    {{-- Área de Resultados --}}
    @if($resultadoBusqueda)
        <div class="bg-white shadow-md rounded-lg p-6 center rounded-lg mb-6">
            @if($miembroEncontrado)
                <div class="items-center mb-6 pb-6 border-b border-neutral-200">
                    @if ($miembroEncontrado->foto_path)
                        <img class="h-20 w-20 sm:h-25 sm:w-25 rounded-full object-cover mr-0 sm:mr-6 mb-4 sm:mb-0 shadow-md" src="{{ asset('storage/' . $miembroEncontrado->foto_path) }}" alt="Foto de {{ $miembroEncontrado->nombre }}">
                    @else
                        <span class="inline-flex items-center justify-center h-24 w-24 sm:h-32 sm:w-32 rounded-full bg-neutral-300 mr-0 sm:mr-6 mb-4 sm:mb-0 shadow-md">
                            <span class="text-3xl sm:text-4xl font-medium leading-none text-neutral-700">{{ strtoupper(substr($miembroEncontrado->nombre, 0, 1) . substr($miembroEncontrado->apellido, 0, 1)) }}</span>
                        </span>
                    @endif

                    <div class="text-center sm:text-right">
                        <h3 class="text-3xl font-semibold mb-6 pb-6  text-neutral-800">{{ $miembroEncontrado->nombre }} {{ $miembroEncontrado->apellido }}</h3>


                         @php
                            $membresiaRelevanteVista = $miembroEncontrado->membresiaActivaActual ?? $miembroEncontrado->ultimaMembresiaGeneral;
                        @endphp
                   @if  ($diasRestantes <= 1)
                             <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">

                                 <p class="font-bold">¡Atención!</p>
                                 <p>Faltan    {{ $diasRestantes }}  días para la fecha de fin de tu membresía.</p>
                             </div>
                    @endif
                    </div>

                </div>
            @endif

            <div class="p-4 rounded-md text-center text-lg font-semibold
                {{ $resultadoBusqueda == 'acceso_permitido' ? 'bg-success-light text-success-dark' : '' }}
                {{ $resultadoBusqueda == 'acceso_denegado' ? 'bg-danger-light text-danger-dark' : '' }}
                {{ $resultadoBusqueda == 'no_encontrado' ? 'bg-warning-light text-warning-dark' : '' }}
                {{ $resultadoBusqueda == 'sin_membresia_valida' ? 'bg-warning-light text-warning-dark' : '' }}
            ">
                <p>{{ $mensajeResultado }}</p>
            </div>

            @if($resultadoBusqueda == 'acceso_permitido')
                <div class="mt-6 text-center">
                    <button wire:click="registrarEntrada"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-neutral-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{-- <i class="fas fa-check-circle mr-2"></i> --}}
                        <svg class="inline-block h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Registrar Entrada
                    </button>
                </div>
            @endif
        </div>
    @elseif(strlen($terminoBusqueda) > 0 && !$errors->has('terminoBusqueda'))
        {{-- Mostrar un mensaje si se ha buscado pero no hay resultado (y no es por error de validación) --}}
        <div class="bg-white shadow-md rounded-lg p-6 text-center">
            <p class="text-neutral-600">No se encontraron resultados para "{{ $terminoBusqueda }}".</p>
        </div>
    @endif

    {{-- (Opcional) Lista de Últimos Accesos Manuales --}}
    {{--
    <div class="mt-8 bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold text-neutral-800 mb-3">Últimos Accesos Registrados Manualmente</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 px-3 text-left text-neutral-600">Miembro</th>
                        <th class="py-2 px-3 text-left text-neutral-600">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimosAccesosManuales as $acceso)
                        <tr class="border-b hover:bg-neutral-50">
                            <td class="py-2 px-3">{{ $acceso->miembro->nombre ?? 'N/D' }} {{ $acceso->miembro->apellido ?? '' }}</td>
                            <td class="py-2 px-3">{{ \Carbon\Carbon::parse($acceso->fecha_hora)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-3 px-3 text-center text-neutral-500">No hay accesos manuales recientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    --}}
</div>
