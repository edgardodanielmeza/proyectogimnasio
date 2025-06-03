<div>
    {{-- Breadcrumbs (Placeholder) --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
            </li>
        </ol>
    </nav>

    {{-- Main content for Dashboard --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Widget: Resumen de Membresías -->
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Resumen de Membresías</h2>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span>Miembros Activos:</span>
                    <span class="text-2xl font-bold text-green-500">--</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Por Vencer (7 días):</span>
                    <a href="#" class="text-lg font-semibold text-yellow-500 hover:underline">--</a>
                </div>
                <div class="flex justify-between items-center">
                    <span>Vencidas (7 días):</span>
                    <a href="#" class="text-lg font-semibold text-red-500 hover:underline">--</a>
                </div>
            </div>
        </div>

        <!-- Widget: Accesos Recientes -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Accesos Recientes</h2>
            {{-- Placeholder for Sucursal Filter --}}
            <div class="max-h-64 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr>
                            <th class="text-left p-2">Nombre</th>
                            <th class="text-left p-2">Sucursal</th>
                            <th class="text-left p-2">Hora</th>
                            <th class="text-left p-2">Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-center p-4 text-gray-500">No hay accesos recientes.</td></tr>
                        {{-- Rows for recent access --}}
                    </tbody>
                </table>
            </div>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block text-sm">Ver Todos los Accesos</a>
        </div>

        <!-- Widget: Ingresos Rápidos -->
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Ingresos (Mes Actual)</h2>
            <p class="text-3xl font-bold text-blue-600">$----.--</p>
            {{-- Placeholder for period filters --}}
        </div>

        <!-- Widget: Estado de Dispositivos -->
        <div class="bg-white shadow-lg rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Estado de Dispositivos</h2>
            <div class="space-y-2">
                <div>Online: <span class="text-green-500 font-bold">--</span></div>
                <div>Offline: <span class="text-red-500 font-bold">--</span></div>
                <div>Con Error: <span class="text-yellow-500 font-bold">--</span></div>
            </div>
            <a href="#" class="text-blue-500 hover:underline mt-2 inline-block text-sm">Gestionar Dispositivos</a>
        </div>

        <!-- Widget: Accesos Directos -->
        <div class="bg-white shadow-lg rounded-lg p-4 md:col-span-full"> {{-- Changed to full span for better layout with many buttons --}}
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-4 rounded text-center text-sm">
                    Registrar Nuevo Miembro
                </a>
                <a href="#" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded text-center text-sm">
                    Registrar Pago
                </a>
                <a href="#" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-4 rounded text-center text-sm">
                    Verificar Acceso Manual
                </a>
            </div>
        </div>
    </div>
</div>
