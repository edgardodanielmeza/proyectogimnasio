<div> {{-- Contenedor principal del componente --}}
    {{-- Breadcrumbs y Título --}}
    <div class="mb-6">
        <nav class="flex mb-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <span class="inline-flex items-center text-sm font-medium text-neutral-700">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </span>
                </li>
            </ol>
        </nav>
        {{-- El título se toma del layout principal --}}
    </div>

    {{-- Sección de Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        {{-- Card Miembros Activos --}}
        <div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-success-light flex-shrink-0">
                    {{-- <i class="fas fa-users fa-2x text-success-dark"></i> --}}
                    <svg class="h-8 w-8 text-success-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.08-.986-.234-1.253M15 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500 uppercase">Miembros Activos</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $this->totalMiembrosActivos }}</p>
                </div>
            </div>
        </div>

        {{-- Card Membresías por Vencer --}}
        <div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-warning-light flex-shrink-0">
                    {{-- <i class="fas fa-calendar-times fa-2x text-yellow-600"></i> --}}
                     <svg class="h-8 w-8 text-warning-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zM12 12h.01M12 15h.01M12 18h.01" /></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500 uppercase">Membresías Próx. a Vencer</p>
                    <p class="text-xs text-neutral-500 normal-case -mt-1">(Próximos 7 días)</p>
                    <p class="text-2xl font-semibold text-neutral-900">{{ $this->totalMembresiasPorVencer }}</p>
                </div>
            </div>
        </div>

        {{-- Card Ingresos del Mes --}}
        <div class="bg-white shadow-lg rounded-lg p-6 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-info-light flex-shrink-0">
                    {{-- <i class="fas fa-dollar-sign fa-2x text-blue-600"></i> --}}
                    <svg class="h-8 w-8 text-info-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-500 uppercase">Ingresos del Mes</p>
                    <p class="text-2xl font-semibold text-neutral-900">${{ number_format($this->totalIngresosMesActual, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección de Accesos Directos --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-neutral-800 mb-4">Accesos Directos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('membresias') }}" class="bg-primary hover:bg-primary-dark text-white font-semibold py-4 px-4 rounded-lg shadow-md flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105">
                {{-- <i class="fas fa-address-card fa-2x mb-2"></i> --}}
                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5.5A2.5 2.5 0 0112 3v2.5m0 0V3m0 2.5A2.5 2.5 0 0014.5 8H17M5 10h14M5 14h4m2 0h4m-2-4h.01M12 10h.01M12 6h.01M7 10h.01M7 14h.01" /></svg>
                <span class="text-sm">Gestionar Miembros</span>
            </a>
            <a href="{{ route('tipos-membresia.index') }}" class="bg-secondary hover:bg-secondary-dark text-neutral-800 font-semibold py-4 px-4 rounded-lg shadow-md flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105">
                {{-- <i class="fas fa-tags fa-2x mb-2"></i> --}}
                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 8V3a1 1 0 011-1h3zM9 9a2 2 0 100-4 2 2 0 000 4z" /></svg>
                <span class="text-sm">Tipos de Membresía</span>
            </a>
            <a href="{{ route('sucursales.index') }}" class="bg-accent hover:bg-accent-dark text-white font-semibold py-4 px-4 rounded-lg shadow-md flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105">
                {{-- <i class="fas fa-store-alt fa-2x mb-2"></i> --}}
                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span class="text-sm">Gestionar Sucursales</span>
            </a>
            <a href="{{ route('pagos') }}" class="bg-info hover:bg-info-dark text-white font-semibold py-4 px-4 rounded-lg shadow-md flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105">
                {{-- <i class="fas fa-cash-register fa-2x mb-2"></i> --}}
                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                <span class="text-sm">Facturación y Pagos</span>
            </a>
             <a href="{{ route('accesos.manual') }}" class="bg-neutral-600 hover:bg-neutral-700 text-white font-semibold py-4 px-4 rounded-lg shadow-md flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105">
                {{-- <i class="fas fa-key fa-2x mb-2"></i> --}}
                <svg class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.623 5.9A2.003 2.003 0 0015 15h-1a2 2 0 00-2 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2a2 2 0 00-2-2h-1a2 2 0 00-2 2H3a2 2 0 012-2h1V9a2 2 0 012-2h1V5a2 2 0 012-2h3a2 2 0 012 2v2h3a2 2 0 012 2z" /></svg>
                <span class="text-sm">Registro Acceso Manual</span>
            </a>
        </div>
    </div>

    {{-- Placeholder para otras secciones del dashboard (ej. últimos miembros, actividad reciente) --}}
    {{--
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-3">Últimos Miembros Registrados</h3>
            <p class="text-sm text-neutral-600">Tabla o lista irá aquí...</p>
        </div>
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h3 class="text-lg font-medium text-neutral-900 mb-3">Actividad Reciente de Accesos</h3>
            <p class="text-sm text-neutral-600">Tabla o lista irá aquí...</p>
        </div>
    </div>
    --}}
</div>
