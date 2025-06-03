# 2. Panel de Control Principal (Dashboard)

**Objetivo:** Proveer una vista general y accesos rápidos a las funcionalidades más relevantes según el rol del usuario.

---

## Requisitos Previos:

*   Utiliza el **Layout Principal** como contenedor.
*   El contenido específico se renderiza en el **Área de Contenido Principal**.
*   Título de la página: "Panel de Control".
*   Breadcrumbs: "Dashboard".

---

## Estructura del Contenido del Dashboard:

*   **Disposición General:** `div` con `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6`.
    *   El número de columnas puede variar según la cantidad de widgets y el tamaño de pantalla.

---

## Widgets/Cards:

Cada widget es un `div` con `bg-white shadow-lg rounded-lg p-4`.

### 2.1. Widget: Resumen de Membresías

*   **Título del Widget:** `h2` con `text-lg font-semibold text-gray-700 mb-3`. "Resumen de Membresías".
*   **Contenido:** `div` con `space-y-2`.
    *   **Total de miembros activos:**
        *   `div` con `flex justify-between items-center`.
        *   `span`: "Miembros Activos:"
        *   `span` (contador): `text-2xl font-bold text-green-500`. (Ej: 150)
    *   **Membresías por vencer pronto (próximos 7 días):**
        *   `div` con `flex justify-between items-center`.
        *   `span`: "Por Vencer (7 días):"
        *   `a` (enlace a lista filtrada `miembros?filtro=por_vencer`): `span` (contador) `text-lg font-semibold text-yellow-500 hover:underline`. (Ej: 15)
    *   **Membresías vencidas recientemente (últimos 7 días):**
        *   `div` con `flex justify-between items-center`.
        *   `span`: "Vencidas (7 días):"
        *   `a` (enlace a lista filtrada `miembros?filtro=vencidas_recientes`): `span` (contador) `text-lg font-semibold text-red-500 hover:underline`. (Ej: 5)

### 2.2. Widget: Accesos Recientes

*   **Título del Widget:** `h2` "Accesos Recientes".
*   **Filtro Rápido (Visible para admin global):**
    *   `select` para "Sucursal": "Todas", "Sucursal A", "Sucursal B". `mb-2 p-2 border rounded`.
*   **Contenido:** `div` para la lista/tabla. `max-h-64 overflow-y-auto`.
    *   Tabla (`table` con `w-full text-sm`):
        *   Encabezados: `thead > tr > th` (Nombre, Sucursal, Hora, Resultado).
        *   Cuerpo: `tbody > tr > td`.
            *   Resultado: `span` con color según estado (ej. `text-green-500` para "Permitido", `text-red-500` para "Denegado").
        *   Ejemplo de fila: "Juan Pérez | Suc. Centro | 10:35 AM | Permitido".
    *   Enlace "Ver Todos los Accesos" (`a` tag `text-blue-500 hover:underline mt-2 inline-block`) que lleva a la pantalla de gestión de accesos.

### 2.3. Widget: Ingresos Rápidos (si aplica al rol)

*   **Título del Widget:** `h2` "Ingresos".
*   **Filtros de Periodo:** `div` con `flex space-x-2 mb-2`.
    *   Botones: "Hoy", "Semana", "Mes". Estilo `px-3 py-1 rounded bg-gray-200 hover:bg-gray-300`.
*   **Contenido:**
    *   **Opción 1 (Cifra):**
        *   `p` con `text-3xl font-bold text-blue-600`. (Ej: $1,250.00)
    *   **Opción 2 (Gráfico Simple):** (Usar una librería de gráficos como Chart.js)
        *   `canvas` para un gráfico de barras o líneas simple mostrando ingresos del periodo seleccionado. `h-40`.

### 2.4. Widget: Estado de Dispositivos de Acceso (si el usuario tiene permisos)

*   **Título del Widget:** `h2` "Estado de Dispositivos".
*   **Contenido:** `div` con `space-y-2`.
    *   `div`: "Online: `span` (contador) `text-green-500 font-bold`".
    *   `div`: "Offline: `span` (contador) `text-red-500 font-bold`".
    *   `div`: "Con Error: `span` (contador) `text-yellow-500 font-bold`".
    *   `a` (enlace a gestión de dispositivos `dispositivos/`): "Gestionar Dispositivos" `text-blue-500 hover:underline mt-2 inline-block`.

### 2.5. Widget: Accesos Directos

*   **Título del Widget:** `h2` "Acciones Rápidas".
*   **Contenido:** `div` con `grid grid-cols-1 sm:grid-cols-2 gap-2`.
    *   Botón "Registrar Nuevo Miembro": `a` tag estilizado como botón (`bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded text-center`). Lleva a `miembros/nuevo`.
    *   Botón "Registrar Pago": `a` tag estilizado (`bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded text-center`). Lleva a `pagos/nuevo`.
    *   Botón "Verificar Acceso Manual": `a` tag estilizado (`bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded text-center`). Lleva a `accesos/manual`.

---

## Consideraciones Específicas del Dashboard:

*   **Personalización por Rol:** Los widgets visibles y los datos que muestran pueden variar significativamente según el rol del usuario (Administrador Global, Gerente de Sucursal, Recepcionista).
*   **Rendimiento:** Los datos deben cargarse eficientemente. Considerar la carga asíncrona de widgets si es necesario.
*   **Actualización de Datos:** Algunos widgets (como Accesos Recientes) podrían beneficiarse de actualizaciones en tiempo real o periódicas (ej. cada 30 segundos).

---
