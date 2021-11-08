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
        'agencia',
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

    public function agencias()
    {
        return $this->belongsTo('App\Business\Agencia', 'agencia');
    }
}
