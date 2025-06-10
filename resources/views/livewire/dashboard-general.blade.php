<div class="space-y-4">
    {{-- Breadcrumbs compacto --}}
    <nav class="text-xs">
        <ol class="flex items-center space-x-2 text-neutral-500">
            <li class="flex items-center">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Dashboard
            </li>
        </ol>
    </nav>

    {{-- Cards de estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        {{-- Miembros Activos --}}
        @can('gestionar_miembros')
        <div class="bg-white rounded-lg p-3 shadow-sm border border-neutral-100">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-white-50 mr-3">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.08-.986-.234-1.253M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-neutral-500">Miembros Activos</p>
                    <p class="text-lg font-semibold text-neutral-800">{{ $this->totalMiembrosActivos }}</p>
                </div>
            </div>
        </div>
        @endcan

        {{-- Membresías por Vencer --}}
        @can('gestionar_miembros')
        <div class="bg-white rounded-lg p-3 shadow-sm border border-neutral-100">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-amber-50 mr-3">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-neutral-500">Membresías por Vencer</p>
                    <p class="text-xs text-amber-600">(Próximos 7 días)</p>
                    <p class="text-lg font-semibold text-neutral-800">{{ $this->totalMembresiasPorVencer }}</p>
                </div>
            </div>
        </div>
        @endcan

        {{-- Ingresos del Mes --}}
        @can('ver_informes_facturacion')
        <div class="bg-white rounded-lg p-3 shadow-sm border border-neutral-100">
            <div class="flex items-center">
                <div class="p-2 rounded-full bg-blue-50 mr-3">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-neutral-500">Ingresos del Mes</p>
                    <p class="text-lg font-semibold text-neutral-800">${{ number_format($this->totalIngresosMesActual, 2) }}</p>
                </div>
            </div>
        </div>
        @endcan
    </div>

    {{-- Sección de 2 columnas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @can('gestionar_miembros')
        <div class="bg-white rounded-lg p-3 shadow-sm border border-neutral-100">
            <h3 class="text-sm font-semibold text-neutral-800 mb-2">Últimos Miembros</h3>
            <!-- Contenido de la tabla -->
        </div>
        @endcan
        @can('ver_eventos_acceso')
        <div class="bg-white rounded-lg p-3 shadow-sm border border-neutral-100">
            <h3 class="text-sm font-semibold text-neutral-800 mb-2">Accesos Recientes</h3>
            <!-- Contenido de la tabla -->
        </div>
        @endcan
    </div>

   
</div>





</div>