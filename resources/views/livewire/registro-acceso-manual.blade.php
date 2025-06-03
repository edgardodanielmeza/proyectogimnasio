<div>
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569 9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <span class="text-gray-700">Control de Acceso Manual</span>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Columna Principal: Búsqueda y Detalles del Miembro --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <label for="search_miembro" class="block text-sm font-medium text-gray-700 mb-1">Buscar Miembro (Código, Nombre, Email):</label>
                <input type="text" id="search_miembro" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Escribe para buscar...">

                {{-- Resultados de Búsqueda --}}
                <div id="search_results" class="mt-2 max-h-48 overflow-y-auto border border-gray-200 rounded bg-white">
                    {{-- Ejemplo de resultado --}}
                    {{-- <div class="flex items-center space-x-3 p-2 hover:bg-gray-100 cursor-pointer rounded">
                        <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="Foto">
                        <span class="font-medium">Ana López</span>
                        <span class="text-xs text-green-600">Activa</span>
                    </div>
                    <p class="p-2 text-gray-500">No se encontraron miembros.</p> --}}
                    <p class="p-3 text-center text-gray-400">Los resultados de la búsqueda aparecerán aquí.</p>
                </div>
            </div>

            {{-- Detalles del Miembro Seleccionado --}}
            <div id="miembro_details_container" class="p-6 bg-gray-50 rounded-lg shadow-inner">
                {{-- Inicialmente oculto o con mensaje --}}
                <p class="text-center text-gray-500">Seleccione un miembro de la búsqueda para ver sus detalles.</p>

                {{-- Contenido cuando un miembro es seleccionado (ejemplo) --}}
                {{-- <img id="miembro_foto_grande" class="h-32 w-32 rounded-full mx-auto mb-4 shadow-md object-cover" src="https://via.placeholder.com/128" alt="Foto Miembro">
                <h2 id="miembro_nombre_completo" class="text-2xl font-semibold text-center text-gray-800">Nombre del Miembro</h2>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 mt-4 text-sm">
                    <span>Tipo Membresía:</span> <span id="miembro_tipo_membresia" class="font-medium">Mensual</span>
                    <span>Fecha Fin Membresía:</span> <span id="miembro_fecha_fin" class="font-medium">2024-12-31</span>
                    <span>Sucursal:</span> <span id="miembro_sucursal" class="font-medium">Centro</span>
                    <span>Código Miembro:</span> <span id="miembro_codigo" class="font-medium">GYM001</span>
                </div>
                <div id="acceso_status_indicator" class="p-4 mt-4 text-center text-lg font-bold rounded-md bg-green-100 text-green-700">
                    ACTIVA - ACCESO PERMITIDO
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <button id="btn_registrar_entrada" class="py-2 px-6 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow">Registrar Entrada</button>
                    <a href="#" class="py-2 px-6 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow">Ver Detalles Completos</a>
                </div> --}}
            </div>
        </div>

        {{-- Columna Lateral: Últimos Accesos y Acceso de Invitado --}}
        <div class="space-y-6">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-md font-semibold mb-2 text-gray-700">Últimos Accesos Manuales</h3>
                <table class="w-full text-xs">
                    <thead>
                        <tr>
                            <th class="text-left py-1">Miembro</th>
                            <th class="text-left py-1">Hora</th>
                            <th class="text-left py-1">Recepcionista</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Ejemplo de fila --}}
                        {{-- <tr>
                            <td class="py-1">Carlos Ruiz</td>
                            <td class="py-1">09:15 AM</td>
                            <td class="py-1">Admin</td>
                        </tr> --}}
                        <tr>
                            <td colspan="3" class="text-center py-2 text-gray-400">No hay registros recientes.</td>
                        </tr>
                    </tbody>
                </table>
                <a href="#" class="text-blue-500 text-xs mt-1 inline-block hover:underline">Ver historial completo</a>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-md font-semibold mb-2 text-gray-700">Acceso de Invitado/Día</h3>
                <form id="form_acceso_invitado" class="space-y-3">
                    <input type="text" name="nombre_invitado" placeholder="Nombre del Invitado" class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="text" name="identificacion_invitado" placeholder="Identificación (Opcional)" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                    <select name="tipo_pase_invitado" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                        <option>Pase de Día</option>
                        <option>Prueba Gratis</option>
                    </select>
                    <input type="number" name="monto_cobrado_invitado" placeholder="Monto Cobrado (si aplica)" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                    <button type="submit" class="bg-teal-500 hover:bg-teal-600 text-white py-1.5 px-3 text-sm rounded w-full">Registrar Acceso Invitado</button>
                </form>
            </div>
        </div>
    </div>
</div>
