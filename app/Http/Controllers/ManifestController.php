<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Salida;
use App\Business\Sede;

class ManifestController extends Controller
{
    public function list() {
        $w = date('w');
        $ahora = date('H:i');
        $columna = $this->getDay($w, 0);
        $salida = Salida::all();
        $sede = Sede::all();
        $data = [
            'salida' => $salida,
            'sede' => $sede,
            'columna' => $columna,
            'ahora' => $ahora, 
        ];
        return view('manifest.list')->with($data);
    }
}
