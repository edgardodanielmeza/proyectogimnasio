# 5. Pantalla de Gestión de Clases y Horarios

**Objetivo:** Permitir la definición de tipos de clases, la programación de clases en un calendario y la gestión de instructores (básica).

---

## Requisitos Previos:

*   Utiliza el **Layout Principal** como contenedor.
*   Título de la página: "Gestión de Clases y Horarios".
*   Breadcrumbs: "Dashboard > Clases".

---

## Estructura del Contenido:

*   **Pestañas de Navegación (Tabs):** `div` con `border-b border-gray-200 mb-4`.
    *   `nav` con `flex space-x-4 -mb-px`.
        *   Botón/Enlace "Calendario de Clases" (`aria-current="page"` si activo, `border-indigo-500 text-indigo-600`).
        *   Botón/Enlace "Tipos de Clase" (`border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300`).
        *   Botón/Enlace "Instructores" (`border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300`).

---

### 5.1. Pestaña: Calendario de Clases

*   **Disposición:** `div`.
*   **Controles del Calendario:** `div` con `flex justify-between items-center mb-4`.
    *   Botones "Anterior" / "Siguiente" (para semana/mes).
    *   Selector de Vista: "Semana" / "Mes" / "Día".
    *   Selector de Sucursal (si aplica y el usuario tiene acceso a varias).
    *   Fecha actual mostrada.
    *   Botón "Programar Nueva Clase" (`bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded`). Abre `ModalProgramarClase`.
*   **Área del Calendario:**
    *   `div` que contendrá el componente de calendario (ej. FullCalendar.io o similar).
    *   **Vista Semanal/Mensual:**
        *   Celdas para cada día.
        *   Eventos (clases) renderizados dentro de las celdas.
        *   Cada evento muestra:
            *   `div` con `p-1 rounded-md shadow text-xs` (color de fondo según tipo de clase `bg-blue-200`).
            *   Nombre de la Clase (`font-semibold`).
            *   Instructor (`text-gray-700`).
            *   Hora Inicio - Hora Fin.
            *   Cupos: `ocupados/total` (ej. 8/15).
        *   **Interacción:**
            *   Click en un evento: Abre `ModalDetallesClase` con opciones para editar/cancelar (admin) o inscribirse/desinscribirse (miembro, si esta pantalla es accesible para ellos).
            *   Click en una celda vacía (admin): Podría abrir `ModalProgramarClase` pre-llenando la fecha/hora.

---

### 5.2. Pestaña: Tipos de Clase (Definiciones)

*   **Disposición:** `div`.
*   **Acciones Principales:**
    *   Botón "Crear Nuevo Tipo de Clase" (`bg-green-500 ...`). Abre `ModalCrearEditarTipoClase`.
*   **Tabla de Tipos de Clase:** `div` con `overflow-x-auto bg-white shadow-md rounded-lg`.
    *   **Tabla:** `w-full table-auto`.
        *   Encabezados: Nombre Clase, Descripción, Instructor por Defecto, Capacidad Máx., Duración (min), Acciones.
        *   Cuerpo:
            *   Cada fila representa un tipo de clase.
            *   Acciones por fila: Editar (abre `ModalCrearEditarTipoClase`), Eliminar (con confirmación).

---

### 5.3. Pestaña: Instructores

*   **Disposición:** `div`.
*   **Nota:** Esta podría ser una gestión simplificada. Si se requiere gestión avanzada de usuarios/roles, referirse a la pantalla de Usuarios y extenderla.
*   **Acciones Principales:**
    *   Botón "Añadir Nuevo Instructor" (`bg-teal-500 ...`). Abre `ModalCrearEditarInstructor`.
*   **Tabla de Instructores:**
    *   Encabezados: Foto, Nombre, Apellido, Email, Especialidad(es), Clases Asignadas (contador), Acciones.
    *   Cuerpo:
        *   Acciones por fila: Editar (abre `ModalCrearEditarInstructor`), Desactivar/Activar.

---

## Modales:

### 5.4. Modal: Programar Nueva Clase / Editar Clase Programada

*   **Título:** "Programar Nueva Clase" / "Editar Clase Programada".
*   **Formulario:** `space-y-4`.
    *   **Tipo de Clase:** `select` (poblado desde "Tipos de Clase"). Label "Clase".
    *   **Instructor:** `select` (poblado desde "Instructores"). Label "Instructor".
    *   **Fecha:** `input type="date"`. Label "Fecha".
    *   **Hora Inicio:** `input type="time"`. Label "Hora Inicio".
    *   **Hora Fin:** `input type="time"` (informativo, se calcula por duración del Tipo de Clase o manual). Label "Hora Fin".
    *   **Sucursal:** `select` (si aplica). Label "Sucursal".
    *   **Sala/Área:** `input type="text"`. Label "Sala o Área".
    *   **Cupos Máximos:** `input type="number"` (heredado de Tipo de Clase, puede ser modificable para esta instancia). Label "Cupos".
    *   **Repetir Clase (Opcional):** Checkboxes para días de la semana, fecha fin de repetición.
    *   **Notas Adicionales:** `textarea`. Label "Notas".
    *   Botones "Guardar Clase", "Cancelar". Si es edición: "Eliminar Clase de este día", "Eliminar esta y futuras repeticiones".

### 5.5. Modal: Crear Nuevo Tipo de Clase / Editar Tipo de Clase

*   **Título:** "Crear Tipo de Clase" / "Editar Tipo de Clase".
*   **Formulario:** `space-y-4`.
    *   **Nombre Clase:** `input type="text"`. Label "Nombre de la Clase".
    *   **Descripción:** `textarea`. Label "Descripción".
    *   **Instructor por Defecto (Opcional):** `select`. Label "Instructor Principal/Defecto".
    *   **Capacidad Máxima:** `input type="number"`. Label "Capacidad Máxima".
    *   **Duración (minutos):** `input type="number"`. Label "Duración (en minutos)".
    *   **Color en Calendario (Opcional):** `input type="color"`. Label "Color".
    *   Botones "Guardar Tipo de Clase", "Cancelar".

### 5.6. Modal: Detalles de Clase (para vista de calendario)

*   **Título:** "{Nombre Clase} - {Fecha} {Hora}".
*   **Contenido Informativo:**
    *   Instructor, Sala/Área, Cupos (inscritos/máximos).
    *   Lista de miembros inscritos (si el rol es admin/instructor).
*   **Acciones (varían según rol):**
    *   **Admin/Instructor:** "Editar Clase Programada", "Cancelar Clase", "Imprimir Lista de Asistencia".
    *   **Miembro (si aplica):** "Inscribirme", "Cancelar Inscripción".

### 5.7. Modal: Crear Nuevo Instructor / Editar Instructor

*   **Título:** "Añadir Nuevo Instructor" / "Editar Instructor".
*   **Formulario:** `space-y-4`.
    *   **Nombre:** `input type="text"`.
    *   **Apellido:** `input type="text"`.
    *   **Email:** `input type="email"`. (Podría estar vinculado a una cuenta de Usuario existente).
    *   **Teléfono (Opcional):** `input type="tel"`.
    *   **Especialidad(es):** `input type="text"` (ej. Yoga, Zumba, Pesas. Podría ser un selector múltiple si hay predefinidas).
    *   **Foto (Opcional):** `input type="file"`.
    *   Botones "Guardar Instructor", "Cancelar".

---

## Consideraciones:

*   **Integración de Calendario:** Elegir una librería de calendario robusta (ej. FullCalendar.io, DayPilot) y configurarla adecuadamente.
*   **Gestión de Cupos:** Lógica para manejar listas de espera si los cupos se llenan.
*   **Notificaciones:** A miembros sobre clases canceladas, cambios de horario, confirmaciones de inscripción.
*   **Roles y Permisos:** Instructores solo ven/gestionan sus clases. Administradores gestionan todo. Miembros (si tienen acceso) solo se inscriben/desinscriben.

---
