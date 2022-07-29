<?php

namespace App\Business;

use Illuminate\Database\Eloquent\Model;

class HistoryRUC extends Model
{
    protected $table = 'history_ruc';
    protected $primaryKey = 'ruc';

    protected $fillable = [
        'razon_social',
        
    ];

}
