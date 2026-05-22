<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoSolicitud extends Model
{
    use HasFactory;

    public const ESTADO_PENDIENTE = 'pendiente';
    public const ESTADO_EN_PROCESO = 'en_proceso';
    public const ESTADO_COMPLETADA = 'completada';
    public const ESTADO_CANCELADA = 'cancelada';

    public const ESTADOS = [
        self::ESTADO_PENDIENTE,
        self::ESTADO_EN_PROCESO,
        self::ESTADO_COMPLETADA,
        self::ESTADO_CANCELADA,
    ];

    protected $table = 'producto_solicitudes';

    protected $fillable = [
        'product_id',
        'nombre',
        'email',
        'telefono',
        'mensaje',
        'estado',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getEstadoNombreAttribute(): string
    {
        return match ($this->estado) {
            self::ESTADO_EN_PROCESO => 'En proceso',
            self::ESTADO_COMPLETADA => 'Completada',
            self::ESTADO_CANCELADA => 'Cancelada',
            default => 'Pendiente',
        };
    }
}
