<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class EncargoEstado extends Model
{
    protected $table = 'encargo_estado';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
    ];
}
