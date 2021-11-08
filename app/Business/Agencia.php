<?php

namespace App\Business;

// use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Agencia extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'agencia';
    protected $primaryKey = '_id';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'sede',
    ];

    public function sedes()
    {
        return $this->belongsTo('App\Business\Sede', 'sede');
    }

}
