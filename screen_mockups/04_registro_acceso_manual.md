# 4. Pantalla de Registro de Acceso (Interfaz para Recepcionista/Validación Manual)

**Objetivo:** Permitir al personal (ej. recepcionista) verificar rápidamente el estado de la membresía de un miembro y registrar su acceso manualmente.

---

## Requisitos Previos:

*   Utiliza el **Layout Principal** como contenedor.
*   Título de la página: "Control de Acceso Manual".
*   Breadcrumbs: "Dashboard > Accesos > Registro Manual".

---

## Estructura del Contenido:

### 4.1. Componente Principal de Búsqueda y Verificación

*   **Disposición General:** `div` con `grid grid-cols-1 md:grid-cols-3 gap-6`.
    *   Columna 1-2: Búsqueda y Detalles del Miembro.
    *   Columna 3: Últimos Accesos Registrados / Acceso de Invitado.

*   **Sección de Búsqueda (Columna 1-2):**
    *   `div` con `bg-white p-6 rounded-lg shadow-md`.
    *   **Input de Búsqueda:**
        *   `label for="search_miembro"` "Buscar Miembro (Código, Nombre, Email):" `block text-sm font-medium text-gray-700 mb-1`.
        *   `input type="text" id="search_miembro"` `w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500`.
        *   Spinner/Indicador de carga visible mientras se busca (si la búsqueda es asíncrona).
    *   **Resultados de Búsqueda en Tiempo Real:**
        *   `div id="search_results"` `mt-2 max-h-48 overflow-y-auto`.
        *   Si no hay resultados: `p` "No se encontraron miembros."
        *   Si hay resultados, lista de `div` o `button` por cada miembro:
            *   `flex items-center space-x-3 p-2 hover:bg-gray-100 cursor-pointer rounded`.
            *   `img` (foto pequeña) `h-10 w-10 rounded-full`.
            *   `span` (nombre completo) `font-medium`.
            *   `span` (estado membresía) `text-xs` con color (ej. `text-green-600` para Activa).

### 4.2. Detalles del Miembro Seleccionado (Columna 1-2, debajo de la búsqueda)

*   **Contenedor:** `div id="miembro_details_container"` `mt-4 p-6 bg-gray-50 rounded-lg shadow-inner`. Inicialmente oculto o con mensaje "Seleccione un miembro".
*   **Al seleccionar un miembro:**
    *   **Foto Grande:** `img id="miembro_foto_grande"` `h-32 w-32 rounded-full mx-auto mb-4 shadow-md object-cover`.
    *   **Nombre Completo:** `h2 id="miembro_nombre_completo"` `text-2xl font-semibold text-center text-gray-800`.
    *   **Información Clave:** `div` con `grid grid-cols-2 gap-x-4 gap-y-2 mt-4 text-sm`.
        *   `span` "Tipo Membresía:" `font-medium`. `span id="miembro_tipo_membresia"`.
        *   `span` "Fecha Fin Membresía:" `font-medium`. `span id="miembro_fecha_fin"`.
        *   `span` "Sucursal:" `font-medium`. `span id="miembro_sucursal"`.
        *   `span` "Código Miembro:" `font-medium`. `span id="miembro_codigo"`.
    *   **Estado de Acceso (Destacado):**
        *   `div id="acceso_status_indicator"` `p-4 mt-4 text-center text-lg font-bold rounded-md`.
            *   Si ACTIVA: `bg-green-100 text-green-700`. "ACTIVA - ACCESO PERMITIDO".
            *   Si VENCIDA/SUSPENDIDA/CANCELADA: `bg-red-100 text-red-700`. "VENCIDA/SUSPENDIDA - ACCESO DENEGADO".
            *   Si POR VENCER: `bg-yellow-100 text-yellow-700`. "POR VENCER - ACCESO PERMITIDO".
    *   **Botones de Acción:** `div` con `flex justify-center space-x-3 mt-6`.
        *   **Registrar Entrada:** `button id="btn_registrar_entrada"` `py-2 px-6 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow`. Deshabilitado si el acceso no es permitido.
        *   **Registrar Salida (Opcional):** `button id="btn_registrar_salida"` `py-2 px-6 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg shadow`. Visible solo si se controla entrada/salida y el miembro ya ingresó.
        *   **Ver Detalles Completos:** `a href="/miembros/{id}"` `py-2 px-6 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow`.

### 4.3. Sección Lateral (Columna 3)

*   **Contenedor:** `div` con `space-y-6`.

*   **Sub-Sección: Últimos Accesos Registrados Manualmente**
    *   `div` con `bg-white p-4 rounded-lg shadow-md`.
    *   `h3` "Últimos Accesos Manuales" `text-md font-semibold mb-2`.
    *   Tabla (`table-auto w-full text-xs`):
        *   Encabezados: Miembro, Hora, Tipo (E/S), Recepcionista.
        *   Cuerpo con los últimos 5-7 accesos.
        *   `a href="/accesos/historial"` "Ver historial completo" `text-blue-500 text-xs mt-1 inline-block`.

*   **Sub-Sección: Acceso de Invitado/Día (Opcional, si está permitido por la configuración del sistema)**
    *   `div` con `bg-white p-4 rounded-lg shadow-md`.
    *   `h3` "Acceso de Invitado/Día" `text-md font-semibold mb-2`.
    *   `form id="form_acceso_invitado"` con `space-y-3`.
        *   `input type="text" name="nombre_invitado"` placeholder="Nombre del Invitado" `w-full text-sm`.
        *   `input type="text" name="identificacion_invitado"` placeholder="Identificación (Opcional)" `w-full text-sm`.
        *   `select name="tipo_pase_invitado"` (Día, Prueba Gratis) `w-full text-sm`.
        *   `input type="number" name="monto_cobrado_invitado"` placeholder="Monto Cobrado (si aplica)" `w-full text-sm`.
        *   `button type="submit"` "Registrar Acceso Invitado" `bg-teal-500 hover:bg-teal-600 text-white py-1 px-3 text-sm rounded`.

---

## Flujo de Interacción:

1.  Recepcionista escribe en el campo "Buscar Miembro".
2.  A medida que escribe, aparecen resultados coincidentes debajo.
3.  Recepcionista hace clic en un miembro de la lista.
4.  La sección "Detalles del Miembro Seleccionado" se actualiza con la información del miembro y el estado de acceso.
5.  Si el acceso es permitido, el botón "Registrar Entrada" está habilitado.
6.  Al hacer clic en "Registrar Entrada", se envía una solicitud al backend.
7.  Se muestra una notificación de éxito/error.
8.  La tabla "Últimos Accesos Registrados Manualmente" se actualiza (idealmente en tiempo real o con un refresh suave).

---

## Consideraciones:

*   **Rapidez:** La búsqueda y visualización deben ser extremadamente rápidas.
*   **Claridad Visual:** El estado de acceso (permitido/denegado) debe ser muy obvio.
*   **Auditoría:** Todos los accesos manuales deben quedar registrados con el nombre del recepcionista que realizó la acción (obtenido del usuario logueado).
*   **Hardware:** Considerar si se usará lector de código de barras/QR para el campo de búsqueda.

---
