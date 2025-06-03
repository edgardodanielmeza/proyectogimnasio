# 6. Pantalla de Facturación y Pagos

**Objetivo:** Gestionar todos los aspectos relacionados con los pagos de membresías, otros cobros, y (opcionalmente) la generación de facturas.

---

## Requisitos Previos:

*   Utiliza el **Layout Principal** como contenedor.
*   Título de la página: "Facturación y Pagos".
*   Breadcrumbs: "Dashboard > Pagos".

---

## Estructura del Contenido:

*   **Pestañas de Navegación (Tabs):** `div` con `border-b border-gray-200 mb-4`.
    *   `nav` con `flex space-x-4 -mb-px`.
        *   Botón/Enlace "Pagos Pendientes/Atrasados" (`aria-current="page"` si activo).
        *   Botón/Enlace "Historial de Pagos".
        *   Botón/Enlace "Generación de Facturas" (si la funcionalidad está habilitada).
        *   Botón/Enlace "Informes de Ingresos".

---

### 6.1. Pestaña: Pagos Pendientes/Atrasados

*   **Disposición:** `div`.
*   **Filtros:** `div` con `flex space-x-2 mb-4`.
    *   Sucursal (si aplica): `select`.
    *   Tipo de Membresía: `select`.
    *   Búsqueda por Miembro: `input type="text"`.
*   **Tabla de Pagos Pendientes/Atrasados:** `div` con `overflow-x-auto bg-white shadow-md rounded-lg`.
    *   **Tabla:** `w-full table-auto`.
        *   Encabezados: Miembro (Nombre, Email), Membresía (Tipo, Fecha Fin), Monto Adeudado, Días de Atraso, Fecha Próximo Pago, Acciones.
        *   Cuerpo:
            *   Cada fila es un miembro con un pago pendiente o una membresía por renovar.
            *   **Días de Atraso:** Resaltado en rojo si es > 0.
            *   **Acciones por fila:** `div` con `flex space-x-1`.
                *   Botón "Registrar Pago" (`bg-green-500 ... text-xs py-1 px-2`). Abre `ModalRegistrarPago` pre-cargado.
                *   Botón "Enviar Recordatorio" (`bg-yellow-500 ... text-xs py-1 px-2`). (Envía email/SMS predefinido).
                *   Botón "Ver Detalles Miembro" (`text-blue-500 ...`).
                *   (Opcional) Botón "Suspender Membresía" (`bg-red-500 ...`).

---

### 6.2. Pestaña: Historial de Pagos

*   **Disposición:** `div`.
*   **Filtros:** `div` con `flex flex-wrap gap-2 mb-4 items-center`.
    *   Rango de Fechas: `input type="date"` (Desde) / `input type="date"` (Hasta).
    *   Búsqueda por Miembro: `input type="text"`.
    *   Método de Pago: `select` (Todos, Efectivo, Tarjeta, Transferencia).
    *   Sucursal: `select` (si aplica).
    *   Botón "Aplicar Filtros".
*   **Tabla de Historial de Pagos:**
    *   Encabezados: ID Pago, Fecha Pago, Miembro, Monto, Método Pago, Membresía Asociada (si aplica), Referencia, Sucursal, Acciones.
    *   Cuerpo:
        *   **Acciones por fila:**
            *   Botón "Ver Recibo" / "Ver Factura" (`text-blue-500 ...`). Abre modal/PDF.
            *   (Opcional, Admin) Botón "Reembolsar Pago".
            *   (Opcional, Admin) Botón "Editar Pago".

---

### 6.3. Pestaña: Generación de Facturas (Opcional)

*   **Disposición:** `div`.
*   **Filtros (para buscar pagos no facturados o facturas existentes):**
    *   Rango de Fechas, Miembro, Estado Factura (Generada, No Generada).
*   **Acciones:**
    *   Botón "Generar Factura Manualmente" (Abre `ModalGenerarFactura` donde se busca un pago o miembro).
*   **Tabla de Facturas Generadas:**
    *   Encabezados: N° Factura, Fecha Emisión, Cliente (Miembro), Monto, Estado (Pagada, Pendiente), Acciones.
    *   Cuerpo:
        *   **Acciones por fila:** Ver Factura (PDF), Enviar por Email, Cancelar Factura (si la lógica lo permite).

---

### 6.4. Pestaña: Informes de Ingresos

*   **Disposición:** `div` con `grid grid-cols-1 md:grid-cols-2 gap-6`.
*   **Sección 1: Filtros para Informes**
    *   `div` con `bg-white p-4 rounded-lg shadow`.
    *   `h3` "Seleccionar Parámetros del Informe".
    *   Rango de Fechas: `input type="date"` / `input type="date"`.
    *   Sucursal: `select`.
    *   Tipo de Membresía: `select`.
    *   Método de Pago: `select`.
    *   Botón "Generar Informe".
*   **Sección 2: Resultados del Informe**
    *   `div` con `bg-white p-4 rounded-lg shadow`.
    *   `h3` "Resultados".
    *   **Resumen Numérico:**
        *   Total Ingresos: `span` `text-2xl font-bold`.
        *   Número de Pagos: `span`.
        *   Ingreso Promedio por Pago: `span`.
    *   **(Opcional) Gráfico de Ingresos:**
        *   `canvas` para gráfico de barras/líneas mostrando ingresos a lo largo del tiempo seleccionado, o por categoría.
    *   **(Opcional) Tabla Detallada (resumen de los pagos que componen el informe):**
        *   Similar a la tabla de historial de pagos, pero filtrada por los parámetros del informe.
    *   Botón "Exportar a CSV/Excel".

---

## Modales Comunes:

### 6.5. Modal: Registrar Pago

*   **Accedido desde:** Gestión de Membresías, Pagos Pendientes, Dashboard.
*   **Título:** "Registrar Nuevo Pago".
*   **Formulario:** `space-y-4 bg-white p-6 rounded-lg shadow-xl max-w-lg mx-auto`.
    *   **Buscar/Seleccionar Miembro:**
        *   `input type="text"` para buscar (si no viene pre-cargado).
        *   `div` para mostrar nombre y email del miembro seleccionado.
    *   **Membresía Asociada (Opcional):**
        *   `select id="membresia_id"`: Lista las membresías activas/vencidas del miembro. Label "Pagar Membresía Específica (Opcional)". Si se selecciona, el monto podría auto-llenarse.
    *   **Concepto de Pago (si no es membresía):** `input type="text" id="concepto_pago"`. Label "Otro Concepto".
    *   **Monto:** `input type="number" step="0.01" id="monto_pago" required`. Label "Monto a Pagar".
    *   **Método de Pago:** `select id="metodo_pago"` (Efectivo, Tarjeta de Crédito, Tarjeta de Débito, Transferencia Bancaria, Otro) `required`. Label "Método de Pago".
    *   **Fecha de Pago:** `input type="date" id="fecha_pago"` (default: hoy) `required`. Label "Fecha de Pago".
    *   **Referencia/N° Transacción (Opcional):** `input type="text" id="referencia_pago"`. Label "Referencia".
    *   **Notas (Opcional):** `textarea id="notas_pago"`. Label "Notas Adicionales".
    *   **(Opcional) Emitir Factura:** `input type="checkbox" id="emitir_factura"`. Label "Generar Factura para este pago".
    *   Botones "Confirmar Pago", "Cancelar".

### 6.6. Modal: Ver Recibo/Factura Simplificada

*   **Título:** "Recibo de Pago" / "Factura N° {NumeroFactura}".
*   **Contenido:** (Diseño limpio para impresión o PDF)
    *   Logo y Nombre del Gimnasio.
    *   Datos del Miembro.
    *   Detalles del Pago: Fecha, Monto, Concepto/Membresía pagada, Método de pago.
    *   Mensaje de agradecimiento.
*   Botones "Imprimir", "Descargar PDF", "Cerrar".

### 6.7. Modal: Generar Factura Manual (si aplica)

*   **Título:** "Generar Factura Manualmente".
*   **Formulario:**
    *   Buscar Pago por ID o Miembro/Fecha.
    *   Seleccionar el pago a facturar.
    *   (Opcional) Campos adicionales para datos fiscales si no están en el perfil del miembro.
    *   Botón "Generar Factura".

---

## Consideraciones:

*   **Integración con Pasarelas de Pago:** Si se quieren pagos online, esta sección necesitará integraciones adicionales.
*   **Normativa Fiscal:** La generación de facturas debe cumplir con la normativa local. Considerar campos como impuestos, números de factura secuenciales, etc.
*   **Seguridad:** Manejo seguro de información de pagos.
*   **Automatización:** Recordatorios de pago automáticos, procesamiento de renovaciones automáticas.

---
