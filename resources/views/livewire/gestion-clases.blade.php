<div>
    {{-- Breadcrumbs --}}
    <nav class="text-sm mb-4" aria-label="Breadcrumb">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('dashboard') ?? '#' }}" class="text-gray-500 hover:text-gray-700">Dashboard</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569 9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <span class="text-gray-700">Gestión de Clases</span>
            </li>
        </ol>
    </nav>

    {{-- Pestañas de Navegación --}}
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex space-x-4 -mb-px" aria-label="Tabs">
            {{-- El manejo de la pestaña activa se haría con JS o Livewire cambiando clases --}}
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600" aria-current="page">
                Calendario de Clases
            </a>
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Tipos de Clase
            </a>
            <a href="#" class="whitespace-nowrap py-3 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Instructores
            </a>
        </nav>
    </div>

    {{-- Contenido de la Pestaña Activa --}}
    {{-- Aquí se mostraría el contenido de una de las pestañas, ej. Calendario --}}
    <div id="calendario_clases_content">
        <div class="flex justify-between items-center mb-4">
            <div>
                <button class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50">Anterior</button>
                <button class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50 ml-2">Siguiente</button>
                <span class="ml-4 font-semibold text-lg">Junio 2024</span> {{-- Dinámico --}}
            </div>
            <div>
                <select class="border-gray-300 rounded-md text-sm">
                    <option>Vista Semanal</option>
                    <option>Vista Mensual</option>
                </select>
                <button class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded text-sm">
                    Programar Nueva Clase
                </button>
            </div>
        </div>

        {{-- Área del Calendario (Placeholder) --}}
        <div class="bg-white p-6 rounded-lg shadow-md min-h-[500px]">
            <p class="text-center text-gray-500">El componente de calendario (ej. FullCalendar) irá aquí.</p>
            {{-- Ejemplo de cómo podría lucir una clase en el calendario --}}
            {{-- <div class="bg-blue-200 p-1 rounded-md shadow text-xs mb-1">
                <p class="font-semibold">Clase de Yoga</p>
                <p class="text-gray-700 text-xs">Juan Instructor</p>
                <p class="text-xs">09:00 - 10:00</p>
                <p class="text-xs">Cupos: 8/15</p>
            </div> --}}
        </div>
    </div>

    {{-- Contenido para "Tipos de Clase" (inicialmente oculto o en otra ruta/componente hijo) --}}
    {{-- <div id="tipos_clase_content" class="hidden">
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4">
            Crear Nuevo Tipo de Clase
        </button>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Nombre Clase</th>
                        <th class="px-4 py-2 text-left">Descripción</th>
                        <th class="px-4 py-2 text-left">Capacidad</th>
                        <th class="px-4 py-2 text-left">Duración</th>
                        <th class="px-4 py-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="5" class="text-center p-4 text-gray-500">No hay tipos de clase definidos.</td></tr>
                </tbody>
            </table>
        </div>
    </div> --}}

</div>
