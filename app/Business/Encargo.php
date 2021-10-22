<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Encargo extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'encargo';
    protected $primaryKey = '_id';

    protected $fillable = [
        'doc_envia',
        'nombre_envia',
        'celular_envia',
        'email_envia',
        'fecha_envia',

        'doc_recibe',
        'nombre_recibe',
        'celular_recibe',
        'email_recibe',
        'fecha_recibe',

        'origen',
        'destino',
        'agencia_origen',
        'agencia_destino',
        'medio_pago',
        'documento',
        'documento_serie',
        'documento_numero',
        
        'encargo',
    ];
}
