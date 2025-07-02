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
        'tipo_dispositivo', // Cambiado de 'tipo'
        'identificador_dispositivo',
        'estado',
        'ip_address',
        'mac_address', // Añadido
        'puerto',
        'configuracion_adicional', // Añadido
    ];

    protected $casts = [
        'configuracion_adicional' => 'array', // 'array' es a menudo preferido sobre 'json' para el cast
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
        return $this->hasMany(EventoAcceso::class, 'dispositivo_id'); // Asegurar que el nombre de FK sea correcto
    }
}
