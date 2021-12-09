<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Manifiesto extends Model
{
    protected $table = 'manifiesto';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'fecha',
        'hora',
        'cantidad_item',
        'subtotal_pagado',
        'subtotal_por_pagar',
        'total_general',
        'nombre_archivo',
        'url_documento_pdf',        
    ];

    public function detalles() {
        return $this->hasMany('App\Business\ManifiestoDetalle');
    }
}
