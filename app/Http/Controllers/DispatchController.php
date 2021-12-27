<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Business\Encargo;

class DispatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list() {
        // $encargo = Encargo::all()->sortBy(['fecha_hora_envia', 'fecha_hora_recibe']);
        $encargo = DB::table('encargo')
        ->select('*',
            DB::raw('(select nombre from agencia where id = agencia_origen) as agencia_origen_nombre'),
            DB::raw('(select nombre from agencia where id = agencia_destino) as agencia_destino_nombre')
            )
        ->orderBy('fecha_hora_envia', 'desc')
        ->orderBy('fecha_hora_recibe', 'desc')
        ->paginate(env('PAGINACION_DESPACHOS'));
        
        return view('dispatch.list')->with([ 'encargo' => $encargo, 'menu_despacho_active' => 'active']);;
    }
}
