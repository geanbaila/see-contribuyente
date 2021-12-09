<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'item';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'valor_unitario',
        'igv_unitario',
        'precio_unitario',
        'codigo_item', // nuevo
        'tipo_afectacion_id', // nuevo
    ];

    public function encargo()
    {
        return $this->hasMany('App\Business\Encargo');
    }

    public function tipo_afectaciones() {
        return $this->belongsTo('App\Business\TipoAfectacion', 'tipo_afectacion_id');
    }

}
