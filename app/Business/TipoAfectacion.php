<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class TipoAfectacion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'tipo_afectacion'; // catálogo 7
    protected $primaryKey = '_id';

    protected $fillable = [
        'codigo',
        'descripcion',
    ];
}
