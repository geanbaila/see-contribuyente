<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business\Reporte;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list() {
        $data = [
            'prg_ingresos' => Reporte::getIncome(),
        ];
        return view('report.show')->with($data);
    }
}
