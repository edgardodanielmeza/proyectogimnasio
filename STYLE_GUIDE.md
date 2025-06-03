# Guía de Estilo - Tailwind CSS

Este documento define la paleta de colores, tipografía y estilos base para los componentes visuales de la aplicación.

---

## 1. Paleta de Colores

Se utiliza una paleta de colores personalizada extendiendo la configuración de Tailwind CSS.

### Colores Primarios (Primary)
Usados para acciones principales, enlaces activos, y elementos destacados. Base: Cyan.
- **Primary Light**: `#67e8f9` (Equivalente a `primary-light` o `bg-primary-light`)
- **Primary (Default)**: `#06b6d4` (Equivalente a `primary` o `bg-primary`)
- **Primary Dark**: `#0e7490` (Equivalente a `primary-dark` o `bg-primary-dark`)

### Colores Secundarios (Secondary)
Para elementos complementarios o menos prominentes. Base: Yellow.
- **Secondary Light**: `#fde047` (`secondary-light`)
- **Secondary (Default)**: `#facc15` (`secondary`)
- **Secondary Dark**: `#eab308` (`secondary-dark`)

### Colores de Acento (Accent)
Para notificaciones, badges, o elementos que necesitan destacar visualmente. Base: Orange.
- **Accent Light**: `#fb923c` (`accent-light`)
- **Accent (Default)**: `#f97316` (`accent`)
- **Accent Dark**: `#ea580c` (`accent-dark`)

### Colores Neutros (Neutral)
Gama de grises para texto, fondos, bordes. Base: Slate.
- **Neutral 100**: `#f8fafc` (`neutral-100` o `bg-neutral-100`)
- **Neutral 200**: `#f1f5f9` (`neutral-200`)
- **Neutral 300**: `#e2e8f0` (`neutral-300`) - Comúnmente para bordes.
- **Neutral 400**: `#cbd5e1` (`neutral-400`)
- **Neutral 500**: `#94a3b8` (`neutral-500`) - Texto secundario.
- **Neutral 600**: `#64748b` (`neutral-600`) - Texto principal.
- **Neutral 700**: `#475569` (`neutral-700`) - Texto más oscuro, títulos.
- **Neutral 800**: `#334155` (`neutral-800`)
- **Neutral 900**: `#1e293b` (`neutral-900`) - Fondos oscuros.

### Colores de Estado
Para alertas, notificaciones y feedback visual.
- **Success (Éxito)**:
    - Light: `#86efac` (`success-light`)
    - Default: `#22c55e` (`success`)
    - Dark: `#15803d` (`success-dark`)
- **Warning (Advertencia)**:
    - Light: `#fcd34d` (`warning-light`)
    - Default: `#f59e0b` (`warning`)
    - Dark: `#b45309` (`warning-dark`)
- **Danger (Peligro/Error)**:
    - Light: `#fca5a5` (`danger-light`)
    - Default: `#ef4444` (`danger`)
    - Dark: `#b91c1c` (`danger-dark`)
- **Info (Información)**:
    - Light: `#93c5fd` (`info-light`)
    - Default: `#3b82f6` (`info`)
    - Dark: `#1d4ed8` (`info-dark`)

---

## 2. Tipografía

### Fuentes
- **Fuente Principal (Sans-serif):** `Figtree` (Clase: `font-sans`). Esta es la fuente por defecto para todo el cuerpo de la aplicación.
  *Importada en `resources/views/layouts/app.blade.php` a través de Google Fonts (Bunny Fonts).*
- **Fuente de Display (Opcional):** No se define una fuente de display separada por defecto. Se utiliza `font-sans` para encabezados.

### Tamaños de Texto (Clases de Tailwind)
- `text-xs` (0.75rem)
- `text-sm` (0.875rem) - Común para texto secundario o captions.
- `text-base` (1rem) - Tamaño base para el cuerpo del texto.
- `text-lg` (1.125rem) - Subtítulos o texto destacado.
- `text-xl` (1.25rem) - Títulos de sección pequeños.
- `text-2xl` (1.5rem) - Títulos de sección medianos.
- `text-3xl` (1.875rem) - Títulos de página principales.
- `text-4xl`, `text-5xl`, etc. para encabezados muy grandes.

### Pesos de Fuente (Clases de Tailwind)
- `font-light` (300)
- `font-normal` (400) - Peso base.
- `font-medium` (500) - Para énfasis moderado, labels.
- `font-semibold` (600) - Para títulos, botones.
- `font-bold` (700) - Para énfasis fuerte.

---

## 3. Componentes Visuales Principales (Ejemplos)

### Botones
- **Primario:**
  ```html
  <button class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded shadow-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:ring-opacity-75">Botón Primario</button>
  ```
- **Secundario:**
  ```html
  <button class="bg-secondary hover:bg-secondary-dark text-neutral-800 font-semibold py-2 px-4 rounded shadow-md focus:outline-none focus:ring-2 focus:ring-secondary-light focus:ring-opacity-75">Botón Secundario</button>
  ```
- **Outline (Primario):**
  ```html
  <button class="bg-transparent hover:bg-primary text-primary font-semibold hover:text-white py-2 px-3 border border-primary hover:border-transparent rounded focus:outline-none focus:ring-2 focus:ring-primary-light">Outline Primario</button>
  ```
- **Outline (Neutral):**
  ```html
  <button class="bg-transparent hover:bg-neutral-100 text-neutral-700 font-semibold hover:text-neutral-800 py-2 px-3 border border-neutral-300 hover:border-neutral-400 rounded focus:outline-none focus:ring-2 focus:ring-neutral-200">Outline Neutral</button>
  ```
- **Peligro (Danger):**
  ```html
  <button class="bg-danger hover:bg-danger-dark text-white font-semibold py-2 px-4 rounded shadow-md focus:outline-none focus:ring-2 focus:ring-danger-light">Botón Peligro</button>
  ```
- **Tamaños:**
    - Pequeño: Añadir `py-1 px-2 text-sm`.
    - Grande: Añadir `py-3 px-6 text-lg`.

### Tarjetas (Cards)
```html
<div class="bg-white shadow-lg rounded-lg p-4 md:p-6">
    <h3 class="text-xl font-semibold text-neutral-800 mb-2">Título de la Tarjeta</h3>
    <p class="text-neutral-600">Contenido de la tarjeta...</p>
</div>
```

### Formularios
Se recomienda usar el plugin `@tailwindcss/forms` para un mejor reseteo de estilos base.
- **Labels:**
  ```html
  <label for="nombre_campo" class="block text-sm font-medium text-neutral-700 mb-1">Nombre del Campo</label>
  ```
- **Inputs (Texto, Email, etc.):**
  ```html
  <input type="text" id="nombre_campo" class="block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm placeholder-neutral-400">
  ```
- **Selects:**
  ```html
  <select id="select_campo" class="block w-full pl-3 pr-10 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
      <option>Opción 1</option>
      <option>Opción 2</option>
  </select>
  ```
- **Textarea:**
  ```html
  <textarea id="textarea_campo" rows="3" class="block w-full px-3 py-2 border border-neutral-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm placeholder-neutral-400"></textarea>
  ```
- **Checkbox / Radio:** (Estilos base del plugin `@tailwindcss/forms` son un buen punto de partida)
  ```html
  <input type="checkbox" id="checkbox_campo" class="h-4 w-4 text-primary border-neutral-300 rounded focus:ring-primary">
  <label for="checkbox_campo" class="ml-2 block text-sm text-neutral-700">Acepto los términos</label>
  ```
- **Error de Validación:**
  ```html
  <p class="mt-1 text-xs text-danger">Este campo es requerido.</p>
  ```

### Alertas / Notificaciones
- **Success:**
  ```html
  <div class="bg-success-light border-l-4 border-success text-success-dark p-4" role="alert">
      <p class="font-bold">Éxito</p>
      <p>La operación se completó correctamente.</p>
  </div>
  ```
  Alternativa con fondo más suave:
  ```html
  <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md relative" role="alert">
    <strong class="font-semibold">Éxito!</strong>
    <span class="block sm:inline">Mensaje de éxito.</span>
  </div>
  ```
- **Error (Danger):**
  ```html
  <div class="bg-danger-light border-l-4 border-danger text-danger-dark p-4" role="alert">
      <p class="font-bold">Error</p>
      <p>No se pudo completar la operación.</p>
  </div>
  ```
- **Warning:**
  ```html
  <div class="bg-warning-light border-l-4 border-warning text-warning-dark p-4" role="alert">
      <p class="font-bold">Advertencia</p>
      <p>Hay algo que deberías revisar.</p>
  </div>
  ```
- **Info:**
  ```html
  <div class="bg-info-light border-l-4 border-info text-info-dark p-4" role="alert">
      <p class="font-bold">Información</p>
      <p>Nota informativa para el usuario.</p>
  </div>
  ```

### Badges / Etiquetas
- **Primario:**
  ```html
  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-light text-primary-dark">Primario</span>
  ```
- **Neutral:**
  ```html
  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-200 text-neutral-800">Neutral</span>
  ```
- **Success:**
  ```html
  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-light text-success-dark">Activo</span>
  ```
- **Danger:**
  ```html
  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-danger-light text-danger-dark">Inactivo</span>
  ```

### Tablas (Estilo Base)
```html
<div class="overflow-x-auto shadow-md rounded-lg">
    <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-100">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Columna 1</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-600 uppercase tracking-wider">Columna 2</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">Dato A1</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">Dato B1</td>
            </tr>
            <tr class="bg-neutral-50"> <!-- Fila Alterna -->
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">Dato A2</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">Dato B2</td>
            </tr>
        </tbody>
    </table>
</div>
```

---

Esta guía de estilo debe ser un documento vivo y actualizarse a medida que evolucionan los requisitos de diseño de la aplicación.
