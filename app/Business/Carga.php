<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Carga extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'carga';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
        'valor_unitario',
        'tipo_afectacion',
    ];

    public function encargo()
    {
        return $this->hasMany('App\Business\Encargo');
    }

    public function tipo_afectaciones() {
        return $this->belongsTo('App\Business\TipoAfectacion', 'tipo_afectacion');
    }

}
