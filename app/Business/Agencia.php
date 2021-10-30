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
        'sede_id',
    ];

    public function sede()
    {
        return $this->belongsTo('App\Business\Sede', 'sede_id');
    }

}
