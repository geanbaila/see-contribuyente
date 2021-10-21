<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Sede;
class SaleController extends Controller
{
    public function register(Request $request){
        $data = $request->all();
        return \response()->json([ 'status' => 'OK', 'data' => $data['docEnvia'], 'entidad' => $entidad ]);
    }

    public function show(){
        $sede = Sede::all();
        return view('sale.show')->with([ 'sede' => $sede ]);
    }
}
