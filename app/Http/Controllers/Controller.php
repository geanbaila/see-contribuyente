<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        define('GUIA','G');
        define('BOLETA','B');
        define('FACTURA','F');
    }
    
    public function getBeforeDay($w) {
        $ww = ($w==0) ? 6 : $w-1;
        return $this->getDay($ww, 0);
    }
    
    public function getDay($w, $j = 0) {
        for ($i = 1; $i <= $j; $i++) {
            $w = ($w == 6) ? 0 : $w+1;
        }
        switch($w){
            case 0: 
                $column = "domingo"; break;
            case 1: 
                $column = "lunes"; break;
            case 2: 
                $column = "martes"; break;
            case 3: 
                $column = "miercoles"; break;
            case 4: 
                $column = "jueves"; break;
            case 5: 
                $column = "viernes"; break;
            case 6: 
                $column = "sabado"; break;
            default:
                $response = ['status' => 'fails']; break;
        }
        return $column;
    }
}
