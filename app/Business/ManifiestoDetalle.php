<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class ManifiestoDetalle extends Model
{
    protected $table = 'manifiesto_detalle';
    protected $primaryKey = 'id';

    protected $fillable = [
        'manifiesto_id',
        'encargo_id',
        'subtotal',
        'oferta',
        'pagado',
        'por_pagar',
        'cantidad_item',
        'agencia_origen',
        'agencia_destino',
        'documento_serie',
        'documento_correlativo',
    ];

    public function encargoDetalles()
    {
        return $this->hasMany('App\Business\EncargoDetalle', 'encargo_id', 'encargo_id');
    }

    public function encargos()
    {
        return $this->belongsTo('App\Business\Encargo', 'encargo_id');
    }
}

