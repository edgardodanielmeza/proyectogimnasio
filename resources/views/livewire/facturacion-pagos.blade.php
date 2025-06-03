<div>
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569 9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <span class="text-gray-700">Facturación y Pagos</span>
            </li>
        </ol>
    </nav>

    {{-- Pestañas de Navegación --}}
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-4 -mb-px" aria-label="Tabs">
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600" aria-current="page">
                Pagos Pendientes/Atrasados
            </a>
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Historial de Pagos
            </a>
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Generación de Facturas
            </a>
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Informes de Ingresos
            </a>
        </nav>
    </div>

    {{-- Contenido de la Pestaña Activa (Ejemplo: Pagos Pendientes/Atrasados) --}}
    <div id="pagos_pendientes_content">
        <div class="flex flex-wrap gap-2 mb-4">
            {{-- Filtros --}}
            <select class="border-gray-300 rounded-md text-sm">
                <option>Todas las Sucursales</option>
            </select>
            <input type="text" placeholder="Buscar Miembro..." class="px-3 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <button class="py-1.5 px-3 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">Aplicar Filtros</button>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Miembro</th>
                        <th class="px-4 py-2 text-left">Membresía</th>
                        <th class="px-4 py-2 text-left">Monto Adeudado</th>
                        <th class="px-4 py-2 text-left">Días Atraso</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Fila de ejemplo --}}
                    {{-- <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">Ana Torres (ana.torres@example.com)</td>
                        <td class="px-4 py-2">Anual - Vence 2024-05-15</td>
                        <td class="px-4 py-2">$500.00</td>
                        <td class="px-4 py-2 text-red-600 font-semibold">20 días</td>
                        <td class="px-4 py-2">
                            <button class="bg-green-500 hover:bg-green-600 text-white text-xs py-1 px-2 rounded">Registrar Pago</button>
                            <button class="ml-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs py-1 px-2 rounded">Enviar Recordatorio</button>
                        </td>
                    </tr> --}}
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">No hay pagos pendientes o atrasados.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- Paginación --}}
        <div class="mt-4 text-sm text-gray-600">Paginación aquí.</div>
    </div>

    {{-- Modal Registrar Pago (Placeholder) --}}
    {{-- Este modal se activaría con JS o Livewire. --}}
    {{-- <div id="modal_registrar_pago" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Registrar Pago</h3>
            <form class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Miembro: Juan Pérez</label>
                </div>
                <div>
                    <label for="monto_pago" class="block text-sm font-medium text-gray-700">Monto a Pagar</label>
                    <input type="number" id="monto_pago" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                 <div>
                    <label for="metodo_pago" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                    <select id="metodo_pago" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option>Efectivo</option>
                        <option>Tarjeta de Crédito</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Confirmar Pago</button>
                </div>
            </form>
        </div>
    </div> --}}

</div>
