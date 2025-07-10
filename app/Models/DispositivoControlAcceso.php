<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class DispositivoControlAcceso extends Model
{
    use HasFactory;

    protected $table = 'dispositivos_control_acceso';

    protected $fillable = [
        'sucursal_id',
        'nombre',
        'tipo_dispositivo',
        'identificador_dispositivo',
        'estado',
        'ip_address',
        'mac_address',
        'puerto',
        'configuracion_adicional',
    ];

    protected $casts = [
        'configuracion_adicional' => 'array',
        'puerto' => 'integer',
    ];

    // Constantes para tipos de dispositivo
    public const TIPO_TECLADO_NUMERICO = 'teclado_numerico';
    public const TIPO_BIOMETRICO_HUELLA = 'biometrico_huella';
    public const TIPO_BIOMETRICO_FACIAL = 'biometrico_facial';
    public const TIPO_LECTOR_QR = 'lector_qr';
    public const TIPO_OTRO = 'otro';

    public static $tiposDispositivo = [
        self::TIPO_TECLADO_NUMERICO => 'Teclado Numérico',
        self::TIPO_BIOMETRICO_HUELLA => 'Biométrico Huella',
        self::TIPO_BIOMETRICO_FACIAL => 'Biométrico Facial',
        self::TIPO_LECTOR_QR => 'Lector QR',
        self::TIPO_OTRO => 'Otro',
    ];

    // Constantes para estados del dispositivo
    public const ESTADO_ACTIVO = 'activo';
    public const ESTADO_INACTIVO = 'inactivo';
    public const ESTADO_ERROR = 'error';
    public const ESTADO_MANTENIMIENTO = 'mantenimiento';

    public static $estadosDispositivo = [
        self::ESTADO_ACTIVO => 'Activo',
        self::ESTADO_INACTIVO => 'Inactivo',
        self::ESTADO_ERROR => 'Error',
        self::ESTADO_MANTENIMIENTO => 'Mantenimiento',
    ];

    public function sucursal(): BelongsTo
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function eventosAcceso(): HasMany
    {
        // Asumiendo que la FK en EventoAcceso es 'dispositivo_control_acceso_id' o 'dispositivo_id'
        // Ajusta 'dispositivo_id' si el nombre de la FK es diferente.
        return $this->hasMany(EventoAcceso::class, 'dispositivo_id');
    }
}
