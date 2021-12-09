<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table = 'salida';
    protected $primaryKey = 'id';

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

    public function agencias()
    {
        return $this->belongsTo('App\Business\Agencia', 'agencia_id');
    }
}
