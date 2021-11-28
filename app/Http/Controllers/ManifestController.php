<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Salida;
use App\Business\Sede;
use App\Business\Agencia;
use App\Business\Encargo;

class ManifestController extends Controller
{
    public function list2() {
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
            'menu_manifiesto_active' => 'active',
        ];
        return view('manifest.list')->with($data);
    }
    
    public function list() {
        $agencia = Agencia::all();
        $encargo = Encargo::all();
        $data = [
            'origen' => $agencia,
            'encargo' => $encargo, 
            'menu_manifiesto_active' => 'active',
        ];
        return view('manifest.list')->with($data);
    }
}
