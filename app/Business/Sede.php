<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sede';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'estado',
    ];

    // public function agencia()
    // {
    //     return $this->hasMany('App\Business\Agencia');
    // }


}
