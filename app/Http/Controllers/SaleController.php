<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Sede;
use App\Business\Documento;
class SaleController extends Controller
{
    public function register(Request $request){
        $data = $request->all();
        return \response()->json([ 'status' => 'OK', 'data' => $data['docEnvia'] ]);
    }

    public function show(){
        $sede = Sede::all();
        $documento = Documento::all();
        return view('sale.show')->with([ 'sede' => $sede, 'documento' => $documento ]);
    }
}
