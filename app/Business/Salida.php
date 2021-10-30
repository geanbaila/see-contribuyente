<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Salida extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'salida';
    protected $primaryKey = '_id';

    protected $fillable = [
        'agencia_id',
        'horario',
        'horario_predeterminado',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
    ];

    public function agencia()
    {
        return $this->belongsTo('App\Business\Agencia', 'agencia_id');
    }
}
