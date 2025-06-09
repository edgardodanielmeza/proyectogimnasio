# 3. Pantalla de Gestión de Membresías

**Objetivo:** Permitir la visualización, búsqueda, filtrado y gestión integral de miembros y sus membresías.

---

## Requisitos Previos:

*   Utiliza el **Layout Principal** como contenedor.
*   Título de la página: "Gestión de Miembros y Membresías".
*   Breadcrumbs: "Dashboard > Miembros".

---

## Estructura del Contenido:

### 3.1. Acciones Principales y Filtros

*   **Disposición:** `div` con `flex flex-col sm:flex-row justify-between items-center mb-4 gap-2`.
*   **Botón Principal:**
    *   `a` tag "Registrar Nuevo Miembro" estilizado como botón (`bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded`). Lleva a `miembros/nuevo` o abre modal.
*   **Filtros:** `div` con `flex flex-wrap gap-2 items-center`.
    *   **Búsqueda:** `input type="text"` placeholder="Buscar por nombre, email, código..." `px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:w-auto w-full`.
    *   **Estado Membresía:** `select` (`border-gray-300 rounded-md`): "Todos los Estados", "Activa", "Vencida", "Por Vencer", "Suspendida", "Cancelada".
    *   **Tipo de Membresía:** `select` (`border-gray-300 rounded-md`): "Todos los Tipos", "Mensual", "Trimestral", "Anual". (Poblado dinámicamente).
    *   **Sucursal (si aplica para el rol):** `select` (`border-gray-300 rounded-md`): "Todas las Sucursales", "Sucursal A", "Sucursal B".
    *   Botón `button type="submit"` "Filtrar" (`py-2 px-4 bg-gray-600 text-white rounded hover:bg-gray-700`).

### 3.2. Tabla de Miembros/Membresías

*   **Disposición:** `div` con `overflow-x-auto bg-white shadow-md rounded-lg`.
*   **Tabla:** `table` con `w-full table-auto text-sm text-left text-gray-500`.
    *   **Encabezados (`thead bg-gray-50`):** `tr > th` (scope="col" `px-6 py-3`)
        *   Foto (`w-16`)
        *   Nombre Completo
        *   Email
        *   Membresía Actual (Tipo)
        *   Fecha Fin Membresía
        *   Estado Membresía
        *   Sucursal
        *   Acciones (`w-32 text-right`)
    *   **Cuerpo (`tbody`):** `tr` (clase `bg-white border-b hover:bg-gray-50`) > `td` (`px-6 py-4`)
        *   **Foto:** `img` clase `h-10 w-10 rounded-full object-cover`.
        *   **Nombre Completo:** `text-gray-900 font-medium`.
        *   **Email:** `text-gray-600`.
        *   **Membresía Actual (Tipo):** `span`.
        *   **Fecha Fin Membresía:** `span`.
        *   **Estado Membresía:** `span` con badges de colores:
            *   Activa: `bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full`.
            *   Vencida: `bg-red-100 text-red-800 ...`.
            *   Por Vencer: `bg-yellow-100 text-yellow-800 ...`.
            *   Suspendida: `bg-purple-100 text-purple-800 ...`.
            *   Cancelada: `bg-gray-100 text-gray-800 ...`.
        *   **Sucursal:** `span`.
        *   **Acciones por Fila:** `div` con `flex justify-end space-x-2 items-center`.
            *   **Ver Detalles:** `a` href=`miembros/{id}` con ícono de ojo (`eye-icon` `text-blue-600 hover:text-blue-800`). Tooltip "Ver Detalles".
            *   **Editar Miembro:** `a` href=`miembros/{id}/edit` con ícono de lápiz (`pencil-icon` `text-yellow-600 hover:text-yellow-800`). Tooltip "Editar Miembro".
            *   **Gestionar Membresías:** `button` con ícono de tarjeta (`credit-card-icon` `text-green-600 hover:text-green-800`). Abre modal `ModalGestionMembresias`. Tooltip "Gestionar Membresías".
            *   **Registrar Pago:** `button` con ícono de dólar (`currency-dollar-icon` `text-teal-600 hover:text-teal-800`). Abre modal `ModalRegistrarPago` (pre-cargado con datos del miembro). Tooltip "Registrar Pago".

### 3.3. Paginación

*   **Disposición:** `div` con `mt-4 flex justify-between items-center`.
*   Componente de paginación estándar de Laravel/Livewire (ej. "Anterior", números de página, "Siguiente").

---

## Modales / Páginas Separadas para Formularios:

### 3.4. Formulario de Registro/Edición de Miembro

*   **Puede ser Modal (ej. Livewire modal) o Página Separada (`miembros/nuevo`, `miembros/{id}/edit`).**
*   **Título:** "Registrar Nuevo Miembro" / "Editar Miembro: {Nombre}".
*   **Disposición del Formulario:** `form` con `grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-lg shadow-md`.
*   **Campos:**
    *   Nombre: `input type="text" id="nombre"`. Label "Nombre".
    *   Apellido: `input type="text" id="apellido"`. Label "Apellido".
    *   Email: `input type="email" id="email"`. Label "Email".
    *   Fecha de Nacimiento: `input type="date" id="fecha_nacimiento"`. Label "Fecha de Nacimiento".
    *   Teléfono: `input type="tel" id="telefono"`. Label "Teléfono".
    *   Dirección: `input type="text" id="direccion"`. Label "Dirección".
    *   Foto: `input type="file" id="foto"`. Label "Foto de Perfil". Preview de imagen si se está editando.
    *   Sucursal Asignada: `select id="sucursal_id"`. Label "Sucursal". (Poblado dinámicamente).
    *   Código de Acceso Numérico (Opcional, puede ser auto-generado): `input type="text" id="codigo_acceso"`. Label "Código de Acceso".
    *   Notas Adicionales: `textarea id="notas_miembro"`. Label "Notas Adicionales".
*   **Sección para Primera Membresía (Solo en Registro):**
    *   `h3` "Añadir Primera Membresía".
    *   Tipo de Membresía: `select id="tipo_membresia_id"`. Label "Tipo de Membresía".
    *   Fecha de Inicio: `input type="date" id="fecha_inicio_membresia"`. (Puede ser `today` por defecto). Label "Fecha Inicio".
    *   Fecha de Fin: `input type="date" id="fecha_fin_membresia"` (deshabilitado, se calcula automáticamente o informativo). Label "Fecha Fin (Automática)".
    *   Monto a Pagar (informativo): `span`. Label "Monto".
    *   Notas de Membresía: `textarea id="notas_membresia"`. Label "Notas de la Membresía".
*   **Botones del Formulario:**
    *   `button type="submit"` "Guardar Miembro" / "Actualizar Miembro" (`bg-blue-500 ...`).
    *   `button type="button"` "Cancelar" (cierra modal o vuelve a la lista) (`bg-gray-300 ...`).

### 3.5. Modal: Gestión de Membresías de un Miembro

*   **Título del Modal:** "Gestionar Membresías de: {NombreMiembro}".
*   **Disposición:** `div` con `max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-xl`.
*   **Sección 1: Historial de Membresías**
    *   `h4` "Historial".
    *   Tabla: Tipo, Fecha Inicio, Fecha Fin, Estado, Acciones (ej. Ver Pago, Cancelar si aplica).
*   **Sección 2: Acciones de Membresía**
    *   `div` con `flex space-x-2 mt-4`.
    *   Botón "Añadir Nueva Membresía": Abre sub-formulario/modal para seleccionar tipo, fecha inicio, etc.
    *   Botón "Renovar Membresía": (Visible si hay membresía actual o vencida elegible para renovación). Pre-llena formulario con datos de la última membresía.
*   **Sub-Formulario para Añadir/Renovar Membresía:**
    *   Tipo de Membresía: `select`.
    *   Fecha de Inicio: `input type="date"`.
    *   Notas: `textarea`.
    *   Botón "Confirmar y Proceder al Pago" / "Guardar Cambios".
*   **Otras Acciones (según estado de membresía activa):**
    *   Botón "Cancelar Membresía": Requiere confirmación. Cambia estado a "Cancelada".
    *   Botón "Suspender Membresía": Requiere fechas de inicio/fin de suspensión. Cambia estado a "Suspendida".
*   **Botón "Cerrar" del modal.**

---

## Consideraciones:

*   **Livewire/Alpine.js:** Ideal para la interactividad de modales, filtros y actualizaciones parciales de la tabla.
*   **Permisos:** Las acciones (editar, gestionar membresías, registrar pago) deben estar condicionadas por los permisos del usuario.
*   **Experiencia de Usuario:** Confirmaciones para acciones destructivas (cancelar membresía, eliminar miembro si se implementa). Feedback visual claro.

---
