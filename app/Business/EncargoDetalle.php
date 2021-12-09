<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class EncargoDetalle extends Model
{
    protected $table = 'encargo_detalle';
    protected $primaryKey = 'id';

    protected $fillable = [
        'encargo_id',
        'item_id',
        'codigo_producto',
        'descripcion',
        'cantidad_item',
        'peso',
        'valor_unitario',
        'valor_venta',
        'valor_base_igv',
        'porcentaje_igv',
        'igv_venta',
        'tipo_afectacion',
        'precio_unitario',
    ];
}
