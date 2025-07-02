<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DispositivoControlAcceso as Dispositivo;
use App\Models\Sucursal;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionDispositivos extends Component
{
    use WithPagination;

    public $dispositivoId, $sucursal_id, $nombre, $tipo_dispositivo, $identificador_dispositivo;
    public $estado, $ip_address, $mac_address, $puerto;
    public $configuracion_adicional = []; // Guardará los valores de los campos dinámicos
    public $configFieldsDefinition = []; // Define la estructura de los campos dinámicos para la UI

    public $search = '';
    public $isOpen = false;
    protected $paginationTheme = 'tailwind';

    public $todasLasSucursales;
    public $todosLosTiposDispositivo;
    public $todosLosEstadosDispositivo;

    protected function rules()
    {
        $rules = [
            'sucursal_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255',
            'tipo_dispositivo' => ['required', Rule::in(array_keys(Dispositivo::$tiposDispositivo))],
            'identificador_dispositivo' => ['required', 'string', 'max:255', Rule::unique('dispositivos_control_acceso', 'identificador_dispositivo')->ignore($this->dispositivoId)],
            'estado' => ['required', Rule::in(array_keys(Dispositivo::$estadosDispositivo))],
            'ip_address' => 'nullable|ip',
            'mac_address' => ['nullable', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', Rule::unique('dispositivos_control_acceso', 'mac_address')->ignore($this->dispositivoId)->whereNull('deleted_at')], // Asegurar unicidad si se usa SoftDeletes
            'puerto' => 'nullable|integer|min:1|max:65535',
            // 'configuracion_adicional' => 'array', // Validado implícitamente si los campos hijos se validan
        ];

        // Añadir reglas dinámicas para configuracion_adicional
        foreach ($this->configFieldsDefinition as $field) {
            $rules['configuracion_adicional.' . $field['name']] = $field['rules'] ?? 'nullable|string';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'mac_address.regex' => 'El formato de la dirección MAC no es válido.',
        ];
        foreach ($this->configFieldsDefinition as $field) {
            if (isset($field['messages'])) {
                foreach ($field['messages'] as $rule => $message) {
                    $messages['configuracion_adicional.' . $field['name'] . '.' . $rule] = $message;
                }
            }
        }
        return $messages;
    }

    public function updated($propertyName)
    {
        // Si cambia el tipo de dispositivo, recargar las definiciones de campos de config.
        if ($propertyName === 'tipo_dispositivo') {
            $this->loadConfigFieldsDefinition();
        }
        $this->validateOnly($propertyName);
    }


    public function mount()
    {
        $this->todasLasSucursales = Sucursal::orderBy('nombre')->pluck('nombre', 'id')->toArray();
        $this->todosLosTiposDispositivo = Dispositivo::$tiposDispositivo;
        $this->todosLosEstadosDispositivo = Dispositivo::$estadosDispositivo;
        $this->loadConfigFieldsDefinition(); // Cargar definiciones iniciales
    }

    public function render()
    {
        $this->authorize('gestionar dispositivos acceso');

        $dispositivos = Dispositivo::with('sucursal')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('identificador_dispositivo', 'like', '%' . $this->search . '%')
                      ->orWhere('tipo_dispositivo', 'like', '%' . $this->search . '%')
                      ->orWhereHas('sucursal', function($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('nombre')
            ->paginate(10);

        return view('livewire.gestion-dispositivos', [
            'dispositivos' => $dispositivos,
        ]);
    }

    public function create()
    {
        $this->authorize('gestionar dispositivos acceso');
        $this->resetInputFields();
        // $this->tipo_dispositivo = array_key_first($this->todosLosTiposDispositivo); // Asegurar que se llame a updatedTipoDispositivo
        $this->updatedTipoDispositivo($this->tipo_dispositivo); // Para inicializar configFieldsDefinition y valores
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->dispositivoId = null;
        $this->sucursal_id = null;
        $this->nombre = '';
        $this->tipo_dispositivo = array_key_first(Dispositivo::$tiposDispositivo);
        $this->identificador_dispositivo = '';
        $this->estado = Dispositivo::ESTADO_ACTIVO;
        $this->ip_address = null;
        $this->mac_address = null;
        $this->puerto = null;
        $this->configuracion_adicional = [];
        $this->configFieldsDefinition = []; // Se recargará con loadConfigFieldsDefinition
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->authorize('gestionar dispositivos acceso');

        // Recargar definiciones de campos por si acaso, antes de validar
        $this->loadConfigFieldsDefinition();
        $validatedData = $this->validate();

        // Solo guardar los campos de configuracion_adicional que están definidos
        $configAdicionalParaGuardar = [];
        if (isset($validatedData['configuracion_adicional'])) {
            foreach ($this->configFieldsDefinition as $field) {
                if (array_key_exists($field['name'], $validatedData['configuracion_adicional'])) {
                    $configAdicionalParaGuardar[$field['name']] = $validatedData['configuracion_adicional'][$field['name']];
                }
            }
        }

        $dataToStore = [
            'sucursal_id' => $validatedData['sucursal_id'],
            'nombre' => $validatedData['nombre'],
            'tipo_dispositivo' => $validatedData['tipo_dispositivo'],
            'identificador_dispositivo' => $validatedData['identificador_dispositivo'],
            'estado' => $validatedData['estado'],
            'ip_address' => $validatedData['ip_address'],
            'mac_address' => $validatedData['mac_address'],
            'puerto' => $validatedData['puerto'],
            'configuracion_adicional' => $configAdicionalParaGuardar,
        ];

        Dispositivo::updateOrCreate(['id' => $this->dispositivoId], $dataToStore);

        session()->flash('message',
            $this->dispositivoId ? 'Dispositivo actualizado exitosamente.' : 'Dispositivo creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
        $this->loadConfigFieldsDefinition(); // Resetear definiciones
    }

    public function edit(Dispositivo $dispositivo) // Route model binding
    {
        $this->authorize('gestionar dispositivos acceso');
        $this->resetInputFields();

        $this->dispositivoId = $dispositivo->id;
        $this->sucursal_id = $dispositivo->sucursal_id;
        $this->nombre = $dispositivo->nombre;
        $this->tipo_dispositivo = $dispositivo->tipo_dispositivo;
        $this->identificador_dispositivo = $dispositivo->identificador_dispositivo;
        $this->estado = $dispositivo->estado;
        $this->ip_address = $dispositivo->ip_address;
        $this->mac_address = $dispositivo->mac_address;
        $this->puerto = $dispositivo->puerto;

        // Cargar definiciones y luego los valores guardados
        $this->loadConfigFieldsDefinition(); // Carga las definiciones para el tipo actual
        $this->configuracion_adicional = $dispositivo->configuracion_adicional ?? []; // Carga los valores existentes

        $this->openModal();
    }

    public function delete(Dispositivo $dispositivo)
    {
        $this->authorize('gestionar dispositivos acceso');

        if ($dispositivo->eventosAcceso()->exists()) {
             session()->flash('error', 'Este dispositivo tiene eventos de acceso registrados y no puede ser eliminado. Considere marcarlo como inactivo o en mantenimiento.');
             return;
        }

        $dispositivo->delete();
        session()->flash('message', 'Dispositivo eliminado exitosamente.');
    }

    // Método para cargar las definiciones de campos de configuración adicional
    // Se llama en mount, al cambiar tipo_dispositivo, y al editar.
    public function loadConfigFieldsDefinition()
    {
        $this->configFieldsDefinition = []; // Resetear
        // Aquí puedes definir los campos según $this->tipo_dispositivo
        // Ejemplo:
        switch ($this->tipo_dispositivo) {
            case Dispositivo::TIPO_TECLADO_NUMERICO:
                $this->configFieldsDefinition = [
                    ['name' => 'longitud_codigo', 'label' => 'Longitud del Código Numérico', 'type' => 'number', 'rules' => 'nullable|integer|min:4|max:8', 'default' => 6],
                    ['name' => 'permite_codigo_master', 'label' => 'Permite Código Maestro', 'type' => 'checkbox', 'rules' => 'nullable|boolean', 'default' => false],
                ];
                break;
            case Dispositivo::TIPO_BIOMETRICO_HUELLA:
                $this->configFieldsDefinition = [
                    ['name' => 'sensibilidad', 'label' => 'Sensibilidad del Sensor (1-Baja, 5-Alta)', 'type' => 'number', 'rules' => 'nullable|integer|min:1|max:5', 'default' => 3],
                    ['name' => 'api_endpoint_registro', 'label' => 'API Endpoint para Registro de Huellas', 'type' => 'url', 'rules' => 'nullable|url'],
                    ['name' => 'api_key', 'label' => 'API Key (si aplica)', 'type' => 'password', 'rules' => 'nullable|string'],
                ];
                break;
            case Dispositivo::TIPO_LECTOR_QR:
                 $this->configFieldsDefinition = [
                    ['name' => 'prefijo_qr_esperado', 'label' => 'Prefijo Esperado en QR (opcional)', 'type' => 'text', 'rules' => 'nullable|string|max:50'],
                    ['name' => 'duracion_qr_temporal_seg', 'label' => 'Duración QR Temporal (segundos)', 'type' => 'number', 'rules' => 'nullable|integer|min:5', 'default' => 300],
                ];
                break;
            // No añadir campos para 'otro' o tipos sin config específica.
        }
        // Si estamos editando o creando, inicializar $this->configuracion_adicional con los defaults si no hay valor
        $currentConfigValues = $this->configuracion_adicional ?? [];
        $this->configuracion_adicional = []; // Resetear para asegurar solo campos definidos
        foreach($this->configFieldsDefinition as $field) {
            $this->configuracion_adicional[$field['name']] = $currentConfigValues[$field['name']] ?? ($field['default'] ?? ($field['type'] === 'checkbox' ? false : null));
        }
    }

    // Se llama desde la UI con wire:model.live="tipo_dispositivo"
    public function updatedTipoDispositivo($value)
    {
        $this->loadConfigFieldsDefinition();
        // No es necesario resetear $this->configuracion_adicional aquí si loadConfig ya lo hace bien.
        $this->resetErrorBag('configuracion_adicional.*'); // Limpiar errores de config anterior
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
