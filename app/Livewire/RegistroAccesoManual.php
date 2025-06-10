<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Miembro;
use App\Models\EventoAcceso;
use App\Models\DispositivoControlAcceso;
use App\Models\Sucursal; // Import Sucursal
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class RegistroAccesoManual extends Component
{
    public string $title = "Control de Acceso Manual";

    public $terminoBusqueda = ''; // Usado para buscar miembro por código, nombre, etc.
    public $codigoQrIngresado = ''; // Para el input del código QR
    public $miembroEncontrado;

    public $dispositivosSucursalActual = [];
    public $dispositivoSeleccionadoId;
    public $sucursalUsuarioLogueado;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->sucursal_id) {
            $this->sucursalUsuarioLogueado = Sucursal::find($user->sucursal_id);
            if ($this->sucursalUsuarioLogueado) {
                $this->dispositivosSucursalActual = DispositivoControlAcceso::where('sucursal_id', $this->sucursalUsuarioLogueado->id)
                                                                        ->where('estado', 'activo')
                                                                        ->orderBy('nombre')
                                                                        ->get();
            }
        } elseif ($user && $user->hasRole('Admin')) {
            // Admin sin sucursal asignada, podría cargar todos o permitir seleccionar sucursal
            $this->dispositivosSucursalActual = DispositivoControlAcceso::where('estado', 'activo')
                                                                    ->orderBy('sucursal_id') // Opcional: agrupar por sucursal
                                                                    ->orderBy('nombre')
                                                                    ->get();
        } else {
            session()->flash('error', 'No se pudo determinar la sucursal del usuario o no hay dispositivos activos disponibles.');
            $this->dispositivosSucursalActual = [];
        }
    }

    public function buscarMiembroParaAcceso()
    {
        $this->miembroEncontrado = null;
        $this->resetErrorBag();
        session()->forget(['message', 'error_acceso', 'message_acceso']); // Limpiar mensajes flash previos

        $this->validate(['terminoBusqueda' => 'required|min:3'], [
            'terminoBusqueda.required' => 'El término de búsqueda es obligatorio.',
            'terminoBusqueda.min' => 'El término de búsqueda debe tener al menos 3 caracteres.'
        ]);

        $miembro = Miembro::with([
                            'membresiaActivaActual.tipoMembresia',
                            'sucursal', // Para la sucursal de registro del miembro
                            'ultimaMembresiaGeneral.tipoMembresia'
                        ])
                        ->where(function ($query) {
                            $query->where('nombre', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('apellido', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('email', 'like', '%' . $this->terminoBusqueda . '%')
                                  ->orWhere('codigo_acceso_numerico', $this->terminoBusqueda);
                        })
                        ->first();

        if ($miembro) {
            $this->miembroEncontrado = $miembro;
            session()->flash('message_acceso', 'Miembro encontrado: ' . $miembro->nombre . ' ' . $miembro->apellido . '. Verifique los detalles y proceda a registrar el acceso si es correcto.');
        } else {
            session()->flash('error_acceso', 'Miembro no encontrado con el término de búsqueda: "' . $this->terminoBusqueda . '".');
            // No registrar evento aquí, se hará en validarYRegistrarAcceso si se intenta un acceso sin miembro.
        }
    }

    public function validarYRegistrarAcceso()
    {
        // Limpiar mensajes previos para evitar confusión
        session()->forget(['message_acceso', 'error_acceso', 'message', 'error']);

        $this->validate([
            'dispositivoSeleccionadoId' => 'required',
            // 'terminoBusqueda' y 'codigoQrIngresado' se validan según cuál se use
        ], [
            'dispositivoSeleccionadoId.required' => 'Debe seleccionar un dispositivo de acceso.',
        ]);

        $dispositivo = DispositivoControlAcceso::find($this->dispositivoSeleccionadoId);
        if(!$dispositivo){
            session()->flash('error_acceso', 'Dispositivo seleccionado no válido.');
            return;
        }

        $now = Carbon::now();
        $sucursalDispositivoId = $dispositivo->sucursal_id;
        $miembro = null;
        $metodoAccesoUsadoParaEvento = 'desconocido'; // Default

        if (!empty($this->codigoQrIngresado)) {
            $this->validate(['codigoQrIngresado' => 'string|min:10']); // Longitud mínima para un QR razonable
            $metodoAccesoUsadoParaEvento = 'qr_temporal';
            $miembro = Miembro::where('codigo_qr_temporal', $this->codigoQrIngresado)
                                ->with(['membresiaActivaActual.tipoMembresia', 'sucursal', 'ultimaMembresiaGeneral.tipoMembresia'])
                                ->first();

            if (!$miembro) {
                $this->registrarEventoAcceso(null, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_codigo', 'denegado', 'Código QR no encontrado: ' . $this->codigoQrIngresado, $metodoAccesoUsadoParaEvento);
                session()->flash('error_acceso', 'ACCESO DENEGADO: Código QR no válido o no encontrado.');
                $this->resetCamposPostIntento(true); // true para limpiar QR
                return;
            }

            if (!$miembro->codigo_qr_expira_at || $miembro->codigo_qr_expira_at->isPast()) {
                $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_codigo', 'denegado', 'Código QR expirado.', $metodoAccesoUsadoParaEvento);
                session()->flash('error_acceso', 'ACCESO DENEGADO: Código QR ha expirado.');
                $miembro->invalidarCodigoQrTemporal();
                $this->resetCamposPostIntento(true); // true para limpiar QR
                return;
            }
             // Opcional: Invalidar QR después de un uso exitoso si es de un solo uso
             // $miembro->invalidarCodigoQrTemporal(); // Si se invalida aquí, no podrá reintentar si falla otra validación
        } elseif (!empty($this->terminoBusqueda)) {
            $metodoAccesoUsadoParaEvento = 'manual_recepcion'; // Búsqueda manual
            // Se asume que buscarMiembroParaAcceso() ya fue llamado desde la UI
            if ($this->miembroEncontrado &&
                (str_contains(strtolower($this->miembroEncontrado->nombre . ' ' . $this->miembroEncontrado->apellido), strtolower($this->terminoBusqueda)) ||
                 $this->miembroEncontrado->email == $this->terminoBusqueda ||
                 (isset($this->miembroEncontrado->codigo_acceso_numerico) && $this->miembroEncontrado->codigo_acceso_numerico == $this->terminoBusqueda)
                )
            ) {
                $miembro = $this->miembroEncontrado;
            } else { // Si no hay miembro encontrado o no coincide con la búsqueda actual, intentar buscar de nuevo.
                $this->buscarMiembroParaAcceso();
                if(!$this->miembroEncontrado){
                     $this->registrarEventoAcceso(null, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_desconocido', 'denegado', 'Miembro no encontrado con término: ' . $this->terminoBusqueda, $metodoAccesoUsadoParaEvento);
                     session()->flash('error_acceso', 'Miembro no encontrado. Verifique el término de búsqueda.');
                     $this->resetCamposPostIntento(false); // false para no limpiar terminoBusqueda
                     return;
                }
                $miembro = $this->miembroEncontrado;
            }
        } else {
            session()->flash('error_acceso', 'Debe ingresar un término de búsqueda o un código QR.');
            return;
        }

        // --- INICIO DE VALIDACIONES COMUNES ---
        // $miembro ahora debería estar seteado si se encontró por QR o búsqueda manual
        if (!$miembro) { // Doble chequeo por si acaso
            $this->registrarEventoAcceso(null, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_desconocido', 'denegado', 'No se pudo identificar al miembro.', $metodoAccesoUsadoParaEvento);
            session()->flash('error_acceso', 'Error al identificar al miembro.');
            $this->resetCamposPostIntento(true); // Limpiar todo
            return;
        }

        // 1. Acceso del Miembro Habilitado
        if (!$miembro->acceso_habilitado) {
            $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_otro', 'denegado', 'Acceso del miembro deshabilitado.');
            session()->flash('error', 'ACCESO DENEGADO: El acceso para este miembro está deshabilitado.');
            $this->resetCamposPostIntento();
            return;
        }

        // 2. Membresía Activa
        $membresiaActiva = $miembro->membresiaActivaActual;
        if (!$membresiaActiva) {
            $notas = 'El miembro no tiene una membresía activa o válida.';
            if ($miembro->ultimaMembresiaGeneral) {
                 $notas .= ' Última membresía ('.$miembro->ultimaMembresiaGeneral->tipoMembresia->nombre.'): '.$miembro->ultimaMembresiaGeneral->estado.' y venció el '.$miembro->ultimaMembresiaGeneral->fecha_fin->format('d/m/Y');
            }
            $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_membresia', 'denegado', $notas);
            session()->flash('error', 'ACCESO DENEGADO: ' . $notas);
            $this->resetCamposPostIntento();
            return;
        }

        // 3. Acceso Multisucursal (Simplificado: se compara la sucursal del dispositivo con la sucursal de registro del miembro)
        // Una lógica más completa podría implicar la sucursal donde se registró la membresía.
        // O si el TipoMembresia tiene un flag 'permite_todas_sucursales'
        if (isset($membresiaActiva->tipoMembresia) && !$membresiaActiva->tipoMembresia->acceso_multisucursal) {
            if ($sucursalDispositivoId != $miembro->sucursal_id) { // Asume que miembro tiene sucursal_id de su registro
                $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_otro', 'denegado', 'Membresía no permite acceso a esta sucursal.');
                session()->flash('error', 'ACCESO DENEGADO: Membresía no válida para esta sucursal.');
                $this->resetCamposPostIntento();
                return;
            }
        }

        // 4. Reglas de Acceso (Horarios/Días)
        $diaSemanaActual = $now->dayOfWeek; // 0 para Domingo, 1 para Lunes, ..., 6 para Sábado
        $horaActual = $now->format('H:i:s');

        $reglasAplicables = \App\Models\ReglaAcceso::where('sucursal_id', $sucursalDispositivoId)
            ->where(function ($query) use ($membresiaActiva) {
                $query->whereNull('tipo_membresia_id')
                      ->orWhere('tipo_membresia_id', $membresiaActiva->tipo_membresia_id);
            })
            ->where('dia_semana', $diaSemanaActual) // Reglas para el día de hoy
            ->get();

        $accesoPermitidoPorHorario = true; // Permitido por defecto si no hay reglas específicas para hoy
        if ($reglasAplicables->isNotEmpty()) {
            $accesoPermitidoPorHorario = false; // Si hay reglas para hoy, debe cumplir al menos una
            foreach ($reglasAplicables as $regla) {
                if ((!$regla->hora_desde || $horaActual >= $regla->hora_desde) && (!$regla->hora_hasta || $horaActual <= $regla->hora_hasta)) {
                    $accesoPermitidoPorHorario = true;
                    break;
                }
            }
        }

        if (!$accesoPermitidoPorHorario) {
            $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'intento_denegado_horario', 'denegado', 'Fuera del horario permitido por las reglas de acceso.');
            session()->flash('error', 'ACCESO DENEGADO: Fuera del horario permitido.');
            $this->resetCamposPostIntento();
            return;
        }

        // Si todas las validaciones pasan
        $this->registrarEventoAcceso($miembro->id, $dispositivo->id, $sucursalDispositivoId, $now, 'entrada_manual_recepcion', 'permitido', 'Acceso manual permitido por recepción.');
        session()->flash('message', 'ACCESO PERMITIDO. Membresía: ' . $membresiaActiva->tipoMembresia->nombre . ' (Vence: ' . Carbon::parse($membresiaActiva->fecha_fin)->format('d/m/Y') . ')');
        $this->resetCamposPostIntento();
    }

    private function registrarEventoAcceso($miembroId, $dispositivoId, $sucursalId, $fechaHora, $tipoEvento, $resultado, $notas = null, $metodoAcceso = 'desconocido')
    {
        EventoAcceso::create([
            'miembro_id' => $miembroId,
            'dispositivo_control_acceso_id' => $dispositivoId,
            'sucursal_id' => $sucursalId,
            'fecha_hora' => $fechaHora,
            'tipo_evento' => $tipoEvento,
            'metodo_acceso_utilizado' => $metodoAcceso,
            'resultado' => $resultado,
            'notas' => $notas,
        ]);
    }

    private function resetCamposPostIntento($limpiarQr = false, $limpiarBusqueda = false)
    {
        if($limpiarBusqueda) {
            $this->terminoBusqueda = '';
        }
        if($limpiarQr) {
            $this->codigoQrIngresado = '';
        }
        $this->miembroEncontrado = null;
        // No limpiar dispositivo seleccionado por si se realizan varios intentos con el mismo.
        // Limpiar mensajes flash se hace al inicio de la acción.
    }

    // private function resetCamposPostIntentoQr() // Ya no es necesario, se usa resetCamposPostIntento(true)
    // {
    //     $this->codigoQrIngresado = '';
    //     $this->miembroEncontrado = null;
    // }


    // public function cargarUltimosAccesos() // Eliminado por ahora
    // {
    // }

    public function render()
    {
        return view('livewire.registro-acceso-manual')
            ->layout('layouts.app', ['title' => $this->title]);
    }
}
