<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Encargo;

class DispatchController extends Controller
{
    public function list() {
        $encargo = Encargo::all()->sortBy('fecha_hora_envia');
        return view('dispatch.list')->with([ 'encargo' => $encargo ]);;
    }
}
