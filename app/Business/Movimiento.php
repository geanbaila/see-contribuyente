<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Movimiento extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'movimiento';
    protected $primaryKey = '_id';
    
    protected $fillable = [
        "salida_id",
        "placa",
        "inicio_horario",
        "inicio_agenda_id",
        "fin_agenda_id",
        "fin_horario",
    ];
}
