<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DispositivoControlAcceso as Dispositivo; // Alias para usar Dispositivo
use App\Models\Sucursal;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class GestionDispositivos extends Component
{
    use WithPagination;

    public $dispositivoId, $sucursal_id, $nombre, $tipo_dispositivo, $identificador_dispositivo;
    public $estado, $ip_address, $mac_address, $puerto;
    public $configuracion_adicional = []; // Se manejará como array en el componente

    public $search = '';
    public $isOpen = false;
    protected $paginationTheme = 'tailwind';

    public $todasLasSucursales;
    public $todosLosTiposDispositivo;
    public $todosLosEstadosDispositivo;

    // Para campos de configuración adicional dinámicos
    public $configFields = [];


    protected function rules()
    {
        return [
            'sucursal_id' => 'required|exists:sucursales,id',
            'nombre' => 'required|string|max:255',
            'tipo_dispositivo' => ['required', Rule::in(array_keys(Dispositivo::$tiposDispositivo))],
            'identificador_dispositivo' => ['required', 'string', 'max:255', Rule::unique('dispositivos_control_acceso', 'identificador_dispositivo')->ignore($this->dispositivoId)],
            'estado' => ['required', Rule::in(array_keys(Dispositivo::$estadosDispositivo))],
            'ip_address' => 'nullable|ip',
            'mac_address' => ['nullable', 'regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', Rule::unique('dispositivos_control_acceso', 'mac_address')->ignore($this->dispositivoId)],
            'puerto' => 'nullable|integer|min:1|max:65535',
            'configuracion_adicional' => 'nullable|array',
            // Reglas dinámicas para configuracion_adicional podrían ir aquí si es necesario
            // 'configuracion_adicional.campo_especifico' => 'required_if:tipo_dispositivo,algun_tipo|string',
        ];
    }

    public function messages()
    {
        return [
            'mac_address.regex' => 'El formato de la dirección MAC no es válido.',
            // Añadir más mensajes personalizados si se desea
        ];
    }

    public function mount()
    {
        $this->todasLasSucursales = Sucursal::pluck('nombre', 'id')->toArray();
        $this->todosLosTiposDispositivo = Dispositivo::$tiposDispositivo;
        $this->todosLosEstadosDispositivo = Dispositivo::$estadosDispositivo;
    }

    public function render()
    {
        $this->authorize('gestionar dispositivos acceso'); // Autorización general para ver la lista

        $dispositivos = Dispositivo::with('sucursal')
            ->where(function($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('identificador_dispositivo', 'like', '%' . $this->search . '%')
                      ->orWhereHas('sucursal', function($q) {
                          $q->where('nombre', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate(10);

        return view('livewire.gestion-dispositivos', [
            'dispositivos' => $dispositivos,
        ]);
    }

    public function create()
    {
        $this->authorize('gestionar dispositivos acceso'); // O un permiso más específico como 'crear dispositivo'
        $this->resetInputFields();
        $this->updatedTipoDispositivo($this->tipo_dispositivo); // Para inicializar configFields
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetErrorBag();
    }

    private function resetInputFields()
    {
        $this->dispositivoId = null;
        $this->sucursal_id = null;
        $this->nombre = '';
        $this->tipo_dispositivo = array_key_first(Dispositivo::$tiposDispositivo) ?? null; // Default al primer tipo
        $this->identificador_dispositivo = '';
        $this->estado = Dispositivo::ESTADO_ACTIVO; // Default a activo
        $this->ip_address = null;
        $this->mac_address = null;
        $this->puerto = null;
        $this->configuracion_adicional = [];
        $this->configFields = [];
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->authorize('gestionar dispositivos acceso'); // O 'crear dispositivo' / 'editar dispositivo'
        $validatedData = $this->validate();

        // Combinar configuracion_adicional de los campos dinámicos
        $configAdicionalParaGuardar = [];
        foreach ($this->configFields as $field) {
            if (isset($this->configuracion_adicional[$field['name']])) {
                $configAdicionalParaGuardar[$field['name']] = $this->configuracion_adicional[$field['name']];
            }
        }
        $validatedData['configuracion_adicional'] = $configAdicionalParaGuardar;


        Dispositivo::updateOrCreate(['id' => $this->dispositivoId], $validatedData);

        session()->flash('message',
            $this->dispositivoId ? 'Dispositivo actualizado exitosamente.' : 'Dispositivo creado exitosamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->authorize('gestionar dispositivos acceso'); // O 'editar dispositivo'
        $dispositivo = Dispositivo::findOrFail($id);
        $this->dispositivoId = $id;
        $this->sucursal_id = $dispositivo->sucursal_id;
        $this->nombre = $dispositivo->nombre;
        $this->tipo_dispositivo = $dispositivo->tipo_dispositivo;
        $this->identificador_dispositivo = $dispositivo->identificador_dispositivo;
        $this->estado = $dispositivo->estado;
        $this->ip_address = $dispositivo->ip_address;
        $this->mac_address = $dispositivo->mac_address;
        $this->puerto = $dispositivo->puerto;
        $this->configuracion_adicional = $dispositivo->configuracion_adicional ?? [];

        $this->updatedTipoDispositivo($this->tipo_dispositivo); // Para cargar configFields con valores
        $this->openModal();
    }

    public function delete($id)
    {
        $this->authorize('gestionar dispositivos acceso'); // O 'eliminar dispositivo'
        $dispositivo = Dispositivo::findOrFail($id);

        // Validar si tiene eventos de acceso asociados, si es necesario
        if ($dispositivo->eventosAcceso()->count() > 0) {
             session()->flash('error', 'Este dispositivo tiene eventos de acceso registrados y no puede ser eliminado. Considere marcarlo como inactivo.');
             return;
        }

        $dispositivo->delete();
        session()->flash('message', 'Dispositivo eliminado exitosamente.');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Método para manejar campos dinámicos según el tipo de dispositivo
    public function updatedTipoDispositivo($value)
    {
        $this->configFields = [];
        $currentConfig = $this->configuracion_adicional ?? [];
        $this->configuracion_adicional = []; // Resetear para evitar llevar datos de otros tipos

        switch ($value) {
            case Dispositivo::TIPO_TECLADO_NUMERICO:
                $this->configFields = [
                    // ['name' => 'longitud_codigo', 'label' => 'Longitud del Código', 'type' => 'number', 'value' => $currentConfig['longitud_codigo'] ?? 6],
                ];
                break;
            case Dispositivo::TIPO_BIOMETRICO_HUELLA:
                $this->configFields = [
                    // ['name' => 'sensibilidad_sensor', 'label' => 'Sensibilidad del Sensor (1-5)', 'type' => 'number', 'value' => $currentConfig['sensibilidad_sensor'] ?? 3],
                    // ['name' => 'api_endpoint', 'label' => 'API Endpoint (si aplica)', 'type' => 'text', 'value' => $currentConfig['api_endpoint'] ?? ''],
                ];
                break;
            // Añadir más casos para otros tipos de dispositivos
        }
        // Llenar los valores actuales si existen
        foreach ($this->configFields as $key => $field) {
            if (isset($currentConfig[$field['name']])) {
                $this->configuracion_adicional[$field['name']] = $currentConfig[$field['name']];
                 $this->configFields[$key]['value'] = $currentConfig[$field['name']]; // Para el input
            } else {
                 // Asegurarse de que el modelo tenga un valor por defecto si no hay nada en currentConfig
                 $this->configuracion_adicional[$field['name']] = $field['value'] ?? null;
            }
        }
    }
}
