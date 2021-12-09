<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class ManifiestoDetalle extends Model
{
    protected $table = 'manifiesto_detalle';
    protected $primaryKey = 'id';

    protected $fillable = [
        'manifiesto_id',
        'subtotal',
        'oferta',
        'cantidad_item',
        'agencia_origen',
        'agencia_destino',
        'documento_serie',
        'documento_correlativo',
    ];
}
