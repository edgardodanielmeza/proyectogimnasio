# 1. Layout Principal (Contenedor de todas las vistas)

**Objetivo:** Definir la estructura base de la aplicación que contendrá todas las demás vistas y funcionalidades.

---

## Componentes Principales:

### 1.1. Barra de Navegación Superior (Navbar)

*   **Disposición:** `flex` container, `justify-between` para alinear elementos a izquierda y derecha, `items-center`. Padding `py-4 px-6`. Fondo `bg-gray-800`, Texto `text-white`.
*   **Componentes Izquierda:**
    *   **Logo de la aplicación:** `img` tag, `h-10 w-auto`. Configurable desde panel de administración.
    *   **Nombre del gimnasio:** `span` tag, `text-xl font-semibold ml-3`. Configurable.
*   **Componentes Centro (Menú Principal):**
    *   `nav` tag con `ul` `flex space-x-4`.
    *   Enlaces (`a` tags) a secciones principales:
        *   "Dashboard" (`/dashboard`)
        *   "Miembros" (`/miembros`)
        *   "Clases" (`/clases`)
        *   "Accesos" (`/accesos`)
        *   "Pagos" (`/pagos`)
        *   "Configuración" (`/configuracion`) - Visible solo para roles administradores.
    *   Estilo de enlace activo: `text-blue-400 font-semibold`.
*   **Componentes Derecha:**
    *   **Notificaciones:**
        *   `button` con ícono de campana (`bell-icon`).
        *   Badge contador (`span` con `bg-red-500 text-white text-xs rounded-full absolute -top-1 -right-1 px-1`).
        *   Al hacer clic, muestra un dropdown (`div` posicionado absolutamente) con la lista de notificaciones.
    *   **Información del Usuario Logueado:** `div` con `flex items-center space-x-2 mr-4`.
        *   `span` para Nombre del Usuario (`text-sm`).
        *   `span` para Rol del Usuario (`text-xs text-gray-400`).
    *   **Menú Desplegable del Usuario:**
        *   `button` con avatar del usuario (o ícono de usuario) y una flecha hacia abajo (`chevron-down-icon`).
        *   Al hacer clic, muestra un dropdown (`div` posicionado absolutamente, `bg-white text-gray-700 rounded shadow-lg py-1`) con enlaces:
            *   "Mi Perfil" (`/perfil`)
            *   "Configuración de Cuenta" (`/cuenta/configuracion`)
            *   "Cerrar Sesión" (`/logout` - POST request)

### 1.2. Barra Lateral (Sidebar)

*   **Condición:** Puede ser opcional o integrada/simplificada para ciertos roles/vistas. Si es visible, se muestra a la izquierda del Área de Contenido Principal.
*   **Disposición:** `div` con `w-64 bg-gray-700 text-white p-4 space-y-2`.
*   **Colapsable:** Botón para minimizar (`<< icon`) y expandir (`>> icon`). Cuando está colapsada, solo muestra iconos.
*   **Contenido:**
    *   Título de la sección actual (ej. "Gestión de Miembros").
    *   Enlaces de navegación secundarios (`ul` con `li > a`):
        *   Ej. para "Miembros": "Ver Todos", "Añadir Nuevo", "Reportes de Miembros".
        *   Estilo de enlace activo: `bg-blue-500 rounded`.

### 1.3. Área de Contenido Principal

*   **Disposición:** `main` tag, `flex-1 p-6 bg-gray-100`. Ocupa el espacio restante.
*   **Componentes:**
    *   **Título de la Página Actual:** `h1` tag, `text-2xl font-semibold text-gray-800 mb-2`.
    *   **Breadcrumbs (Migas de Pan):**
        *   `nav` con `ol` `flex space-x-2 text-sm text-gray-500 mb-4`.
        *   Ej: `Dashboard > Miembros > Editar Miembro`.
        *   Cada item es un enlace (`a` tag) excepto el último.
    *   **Contenedor del Contenido Específico:** `div` id="page-content". Aquí se inyectará el contenido de cada vista (Dashboard, Gestión de Membresías, etc.).

### 1.4. Pie de Página (Footer)

*   **Disposición:** `footer` tag, `bg-gray-200 text-gray-600 text-center p-4 text-sm`.
*   **Componentes:**
    *   **Copyright:** `p` tag - "© AAAA NombreGimnasio. Todos los derechos reservados."
    *   **Versión de la aplicación:** `p` tag - "Versión X.Y.Z".

---

## Consideraciones Adicionales:

*   **Responsividad:** Todos los componentes deben ser diseñados pensando en responsividad (ej. Navbar se colapsa en un menú hamburguesa en móviles, Sidebar se oculta o se superpone).
*   **Estado de Carga:** Indicadores visuales para cuando el contenido de la página está cargando.
*   **Notificaciones Toast:** Sistema global para mostrar mensajes de éxito, error, información (ej. "Miembro guardado correctamente"). Posicionado usualmente en `top-right` o `bottom-right`.

---
