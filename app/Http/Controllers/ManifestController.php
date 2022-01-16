<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Business\Salida;
use App\Business\Sede;
use App\Business\Agencia;
use App\Business\Encargo;
use App\Business\Manifiesto;
use MongoDB\BSON\ObjectId;
use PDF;

class ManifestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
    
    public function list(Request $request) {
        $en_manifiesto = new ObjectId('61af909ad3f9efe2cb27e8be');
        $agencia_origen_selected = ((int)$request->input('agencia_origen') > 0)? (int)$request->input('agencia_origen'): 0;
        
        if(!empty($request->input('fecha_recibe'))) {
            list($d,$m,$y) = explode('/', $request->input('fecha_recibe'));
            $fecha_recibe = (checkdate($m,$d,$y))? $request->input('fecha_recibe'): date('d/m/Y');
        } else {
            $fecha_recibe = date('d/m/Y');
            list($d,$m,$y) = $fecha_recibe;
        }
        
        
        $encargo = Encargo::where('estado', '!=', $en_manifiesto)
        ->where('agencia_origen', $agencia_origen_selected)
        ->where('documento_fecha', $y.'-'.$m.'-'.$d)
        ->get()->sortBy(['documento_fecha','documento_hora','agencia_destino']);
        /* $encargo = DB::table('encargo')
        ->select(
            '*',
            DB::raw('(select nombre from encargo_estado where id = estado) as estado_nombre'),
            DB::raw('(select departamento from agencia where id = agencia_origen) as agencia_origen_departamento'),
            DB::raw('(select departamento from agencia where id = agencia_destino) as agencia_destino_departamento')
        )
        ->orderBy('agencia_destino', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(env('PAGINACION_MANIFIESTO')); */


        $manifiesto = Manifiesto::all()->sortByDesc('created_at')->take(100);
        $agencia_origen = Agencia::all();
        $data = [
            'agencia_origen' => $agencia_origen,
            'encargo' => $encargo, 
            'manifiesto' => $manifiesto,
            'fecha_recibe' => $fecha_recibe,
            'agencia_origen_selected' => (int) $agencia_origen_selected,
            'menu_manifiesto_active' => 'active',
        ];
        return view('manifest.list')->with($data);
    }

    public function escribirPDF($manifiesto) {
        PDF::SetTitle($manifiesto->nombre_archivo);
        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);
        PDF::SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        PDF::AddPage();
        
        
        PDF::SetFillColor(255, 255, 255);
        PDF::SetTextColor(0);
        $font_size_grande = 9;
        $font_size_gigante = 14;
        $font_size_regular = 9;
        
        $border = '1';
        $align_center = 'C';
        $align_left = 'L';
        $align_right = 'R';
        $height = '';
        $y = '';
        $x = ''; // 5
        PDF::SetFont('times', '', $font_size_regular);
        PDF::setCellPaddings( $left = '', $top = '0.5', $right = '', $bottom = '0.5');

        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        PDF::Cell(24+15+15+40+45+40+10, $height, env('EMPRESA_COMERCIAL'), '', 0, 'L', 0);
        PDF::Ln();
        PDF::Cell(24+15+15+40+45+40+10, $height, env('EMPRESA_DIRECCION'), '', 0, 'L', 0);
        PDF::Ln();
        PDF::SetFont('times', '', $font_size_gigante);
        PDF::Cell(24+15+15+40+45+40+10, $height, 'ENCOMIENDAS / CARGAS', '', 0, 'C', 0);
        PDF::Ln();
        PDF::Cell(24+15+15+40+45+40+10, $height, $manifiesto->ruta, '', 0, 'C', 0);
        PDF::Ln();
        PDF::Ln();
        PDF::SetFont('times', '', $font_size_regular);
        PDF::Cell(24, $height, 'DESPACHADOR:', '', 0, 'R', 1);
        PDF::Cell(15+15+40, $height, ' José María Vargas López ', '', 0, 'L', 1);
        PDF::Cell(80, $height, 'FECHA: ', '', 0, 'R', 1);
        PDF::Cell(15, $height, $manifiesto->fecha, '', 0, 'R', 1);
        PDF::Ln();
        PDF::Cell(24+15+15+40+80, $height, 'HORA: ', '', 0, 'R', 1);
        PDF::Cell(15, $height, $manifiesto->hora, '', 0, 'R', 1);
        PDF::Ln();

        PDF::Cell(24, $height, "CPE", $border, 0, 'L', 1);
        PDF::Cell(15, $height, "POR PAGAR", $border, 0, 'L', 1);
        PDF::Cell(15, $height, "PAGADO", $border, 0, 'L', 1);
        PDF::Cell(40, $height, "CONSIGNA", $border, 0, 'L', 1);
        PDF::Cell(40, $height, "DESTINO", $border, 0, 'L', 1);
        PDF::Cell(45, $height, "DESCRIPCIÓN", $border, 0, 'L', 1);
        PDF::Cell(10, $height, "ÍTEMS", $border, 0, 'L', 1);
        PDF::Ln();

        $peso = 0;
        $por_pagar = 0;
        $pagado = 0;
        $resumen = [];
        foreach($manifiesto->detalles as $item):
            $row_agencia = Agencia::where('id', $item->agencia_destino)->get(['id', 'nombre'])->toArray();
            $por_pagar += $item['por_pagar'];
            $pagado += $item['pagado'];

            PDF::Cell(24, $height, $item['documento_serie'] . '-' . $item['documento_correlativo'], '', 0, 'L', 1);
            PDF::Cell(15, $height, number_format($item['por_pagar'], 2, '.', ''), '', 0, 'L', 1);
            PDF::Cell(15, $height, number_format($item['pagado'], 2, '.', ''), '', 0, 'L', 1);
            PDF::Cell(40, $height, $item->encargos->nombre_recibe, '', 0, 'L', 1, '', 0);
            PDF::Cell(40, $height, $row_agencia[0]['nombre'], '', 0, 'L', 1);
            
            foreach($item->encargoDetalles as $key=> $item2):
                if (array_key_exists($item2->codigo_producto, $resumen)) {
                    $peso += $item2->peso;
                    $resumen[$item2->codigo_producto]['cantidad_item'] += $item2->cantidad_item;
                } else {
                    $peso += $item2->peso;
                    $resumen[$item2->codigo_producto]['cantidad_item'] = $item2->cantidad_item; 
                    $resumen[$item2->codigo_producto]['descripcion'] = $item2->descripcion; 
                }
                
                PDF::Cell(45, $height, $item2->descripcion, '', 0, 'L', 1);
                PDF::Cell(10, $height, $item2->cantidad_item, '', 0, 'R', 1);
                PDF::Ln();
                $n = count($item->encargoDetalles);
                if ($n > 1 && $n > $key+1) {
                    PDF::Cell(24, $height, '', '', 0, 'L', 1);
                    PDF::Cell(15, $height, '', '', 0, 'L', 1);
                    PDF::Cell(15, $height, '', '', 0, 'L', 1);
                    PDF::Cell(40, $height, '', '', 0, 'L', 1);
                    PDF::Cell(40, $height, '', '', 0, 'L', 1);
                }
            endforeach;
        endforeach;
        PDF::Ln();
        
        
        
        PDF::Cell(24+15+15+40+45+40+10, $height, '', 'T', 0, 'L', 1);
        PDF::Ln();
        PDF::Cell(24, $height, 'SUBTOTAL:', '', 0, 'R', 1);
        PDF::Cell(15, $height, number_format($por_pagar, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(15, $height, number_format($pagado, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(40, $height, '', '', 0, 'L', 1, '', 0);
        PDF::Cell(40, $height, '', '', 0, 'L', 1);
        PDF::Cell(45, $height, 'TOTAL DE DOCUMENTOS ENVIADOS:', '', 0, 'R', 1);
        PDF::Cell(10, $height, count($manifiesto->detalles), '', 0, 'L', 1);
        PDF::Ln();

        PDF::Cell(24, $height, 'TOTAL GENERAL:', '', 0, 'R', 1);
        PDF::Cell(15, $height, number_format($por_pagar + $pagado, 2, '.', ''), '', 0, 'L', 1);
        PDF::Cell(15, $height, '', '', 0, 'L', 1);
        PDF::Cell(40, $height, '', '', 0, 'L', 1, '', 0);
        PDF::Cell(40, $height, '', '', 0, 'L', 1);
        PDF::Cell(45, $height, 'PESO TOTAl (KG):', '', 0, 'R', 1);
        PDF::Cell(10, $height, $peso, '', 0, 'L', 1);
        PDF::Ln();
        
        PDF::Cell(24, $height, 'RESUMEN:', '', 0, 'R', 1);
        PDF::Ln();

        foreach($resumen as $key => $item):
            PDF::Cell(24, $height, $item['cantidad_item'], '', 0, 'R', 1);
            PDF::Cell(54, $height, $item['descripcion'], '', 0, 'L', 1);
            PDF::Ln();
        endforeach;
        
        PDF::SetY(10);
        PDF::SetFont('times', 'I', $font_size_regular);
        PDF::Cell(0, 10, 'pág. '.PDF::getAliasNumPage().'/'.PDF::getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    
        list($year, $month, $day) = explode('-', $manifiesto->fecha); // yyyy-mm-dd
        $tree = 'resources/manifiesto/' . $year . '/' . $month . '/' . $day;
        $estructura = base_path('public/'.$tree);
        if(!@mkdir($estructura, 0777, true)) {
            if (file_exists($estructura . "/" . $manifiesto->nombre_archivo)) { @unlink($estructura . "/" . $manifiesto->nombre_archivo); }
        }

        $output = public_path($manifiesto->url_documento_pdf);
        PDF::Output($output, 'F');
        PDF::reset();
        
    }
}
