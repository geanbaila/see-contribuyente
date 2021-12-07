<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Manifiesto extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'manifiesto';
    protected $primaryKey = '_id';

    protected $fillable = [
        'fecha',
        'hora',
        'items',
        'url_documento_pdf',
        'nombre_archivo',
        'detalle',
    ];
}
