<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class HistoryDNI extends Model
{
    protected $table = 'history_dni';
    protected $primaryKey = 'dni';
    
    protected $fillable = [
        'nombres',
        
    ];

}
