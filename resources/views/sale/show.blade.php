@extends('layout.layout')
@section('content')
    <style>
        .fw8 {
            font-weight: 800 !important;
        }
        .display-hide {
            display: none;
        }
        .display-show {
            display: block;
        }

    </style>
    <form action="{{ url('/venta/registrar') }}" method="POST">
        <input type="hidden" name="encargo_id" value="{{ isset($encargo) ? $encargo->id : '' }}" />
        <input type="hidden" name="adquiriente" value="{{ (isset($encargo) && empty($encargo->guia_remision_transportista_id)) ? $encargo->adquiriente_id : 0}}" />
        
        <input type="hidden" name="medio_pago" value="{{ isset($encargo) ? $encargo->medio_pago : '' }}">
        <input type="hidden" name="fecha_hora_envia" id="fecha_hora_envia"  value="{{ isset($encargo) ?$encargo->fecha_hora_envia : '' }}" />
        <input type="hidden" name="fecha_hora_recibe" id="fecha_hora_recibe"  value="{{ isset($encargo) ?$encargo->fecha_hora_recibe : '' }}" />
        
        <input type="hidden" name="guia_remision_transportista" value="{{ (isset($encargo) && !empty($encargo->guia_remision_transportista_id)) ? $encargo->guia_remision_transportista_id : 0 }}">
        <input type="hidden" name="url_documento_pdf" value="{{ isset($encargo) ? $encargo->url_documento_pdf : '' }}" />
        
        <input type="hidden" name="nombre_comercial_envia" value="{{ isset($encargo) ? $encargo->nombre_envia : '' }}" />
        <input type="hidden" name="direccion_envia" value="{{ isset($encargo) ? $encargo->id : '' }}" />
        <input type="hidden" name="celular_recibe" value="{{ isset($encargo) ? $encargo->celular_recibe : '' }}">
        <input type="hidden" name="email_recibe" value="{{ isset($encargo) ? $encargo->email_recibe : '' }}">
        
        <input type="hidden" name="nombre_comercial_recibe" value="{{ isset($encargo) ? $encargo->nombre_recibe : '' }}" />
        <input type="hidden" name="direccion_recibe" value="{{ isset($encargo) ? $encargo->id : '' }}" />
        <input type="hidden" name="celular_envia" value="{{ isset($encargo) ? $encargo->celular_envia : '' }}">
        <input type="hidden" name="email_envia" value="{{ isset($encargo) ? $encargo->email_envia : '' }}">
        
        <input type="hidden" name="nombre_comercial_recibe_alternativo" value="{{ isset($encargo) ? $encargo->nombre_recibe_alternativo : '' }}" />
        <input type="hidden" name="direccion_recibe_alternativo" value="{{ isset($encargo) ? $encargo->id : '' }}" />
        <input type="hidden" name="celular_envia_alternativo" value="{{ isset($encargo) ? $encargo->celular_envia_alternativo : '' }}">
        <input type="hidden" name="email_envia_alternativo" value="{{ isset($encargo) ? $encargo->email_envia_alternativo : '' }}">
        

        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-7">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">Envía:</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="doc_envia" name="doc_envia"
                                            value="{{ $encargo->doc_envia }}" />
                                    @else
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="doc_envia" name="doc_envia" value="" />
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">
                                                &nbsp;<img id="loading-input1" style="display:none" src="{{ asset('public/assets/media/loading.gif') }}" width="20" />
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-xxl-7">
                                    <label class="form-label">&nbsp;</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="nombre_envia" name="nombre_envia"
                                            value="{{ $encargo->nombre_envia }}" disabled>
                                    @else
                                        <input type="text" class="form-control" id="nombre_envia" name="nombre_envia"
                                            value="" disabled>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">F. recepción:</label>
                                    @if (isset($encargo) && !empty($encargo->fecha_hora_envia))
                                    @php
                                    list($fecha, $hora) = explode(' ', $encargo->fecha_hora_envia);
                                    list($year, $month, $day) = explode('-', $fecha);
                                    
                                        $fecha_hora_envia_dd_mm_yyyy = $day.'-'.$month.'-'.$year.' '.$hora;
                                    @endphp
                                        <input type="text" class="form-control" name="fecha_hora_envia_blocked" value="{{ $fecha_hora_envia_dd_mm_yyyy }}"
                                         disabled />
                                    @else
                                        <input type="text" class="form-control" name="fecha_hora_envia_blocked" value="" disabled />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-12">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-7">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">Recibe:</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="doc_recibe" name="doc_recibe"
                                            value="{{ $encargo->doc_recibe }}" />
                                    @else
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="doc_recibe" name="doc_recibe" value=""/>
                                         <div class="input-group-append">
                                            <span class="input-group-text" id="basic-addon2">
                                                &nbsp;<img id="loading-input2" style="display:none" src="{{ asset('public/assets/media/loading.gif') }}" width="20" />
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-xxl-7">
                                    <label class="form-label">&nbsp;</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="nombre_recibe" name="nombre_recibe"
                                            value="{{ $encargo->nombre_recibe }}" disabled />
                                    @else
                                        <input type="text" class="form-control" id="nombre_recibe" name="nombre_recibe"
                                            value="" disabled />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">F. entrega:</label>
                                    @if (isset($encargo) && !empty($encargo->fecha_hora_recibe))
                                    @php
                                    list($fecha, $hora) = explode(' ', $encargo->fecha_hora_recibe);
                                    list($year, $month, $day) = explode('-', $fecha);
                                    
                                        $fecha_hora_recibe_dd_mm_yyyy = $day.'-'.$month.'-'.$year.' '.$hora;
                                    @endphp
                                        <input type="text" class="form-control" id="fecha_hora_recibe_blocked" name="fecha_hora_recibe_blocked"
                                            value="{{ $fecha_hora_recibe_dd_mm_yyyy }}" disabled>
                                    @else
                                        <input type="text" class="form-control" id="fecha_hora_recibe_blocked" name="fecha_hora_recibe_blocked"
                                            value="" disabled>
                                    @endif
                            
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-12">
                                    <label>&nbsp;</label>
                                    <a class="float-end" onclick="javascript:addReceivesRow()">
                                        <img src="{{asset('public/assets/media/plus-circle.svg') }}"
                                            width="20" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="RecibeRow" class="display-hide">
                        <div class="row gy-5 g-xl-12">
                            <div class="col-xxl-7">
                                <div class="row gy-5">
                                    <div class="col-xxl-5">
                                        <label>&nbsp;</label>
                                        @if (isset($encargo))
                                            <input type="text" class="form-control" id="doc_recibe_alternativo" name="doc_recibe_alternativo" value="{{ $encargo->doc_recibe_alternativo }}"/>
                                        @else
                                            <input type="text" class="form-control" id="doc_recibe_alternativo" name="doc_recibe_alternativo" value=""/>
                                        @endif
                                    </div>
                                    <div class="col-xxl-7">
                                        <label>&nbsp;</label>
                                        @if (isset($encargo))
                                            <input type="text" class="form-control" id="nombre_recibe_alternativo" name="nombre_recibe_alternativo" value="{{ $encargo->nombre_recibe_alternativo }}" disabled />
                                        @else 
                                            <input type="text" class="form-control" id="nombre_recibe_alternativo" name="nombre_recibe_alternativo" value="" disabled />
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-4">
                                <div class="row gy-5">
                                    <div class="col-xxl-5">&nbsp;</div>
                                </div>
                            </div>
                            <div class="col-xxl-1">
                                <div class="row gy-5">
                                    <div class="col-xxl-12">
                                        <label>&nbsp;</label>
                                        <a class="float-end" onclick="javascript:removeReceivesRow()">
                                            <img class="float-right" src="{{asset('public/assets/media/minus-circle.svg') }}" width="20">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-7">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">Origen:</label>
                                    <select class="form-select" aria-label="--" name="agencia_origen"
                                        onchange="javascript:getSerie();getAgenciaDestino(this.value, false);">
                                        <!-- getSerie();-->
                                        <option> -- </option>
                                        @isset($encargo)
                                                <?php $agencia_origen_selected = $encargo->agencia_origen; ?>
                                        @endisset
                                        @if (count($agencia_origen))
                                            @foreach ($agencia_origen as $item)
                                                @if (isset($agencia_origen_selected))
                                                    <option value="{{ $item->id }}" data-sede="{{ $item->sede_id }}"
                                                        {{ $agencia_origen_selected == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nombre }}</option>
                                                @else
                                                    <option value="{{ $item->id }}" data-sede="{{ $item->sede_id }}">
                                                        {{ $item->nombre }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-7">
                                    <label class="form-label">Agencia destino:</label>
                                    <select class="form-select" aria-label="--" name="agencia_destino"
                                        onchange="javascript:getSerie()">
                                        <option value="--" selected> -- </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="row gy-5">
                                <div class="col-xxl-5">
                                    <label class="form-label">Documento:</label>
                                    <select class="form-select" aria-label="--" id="documento" name="documento"
                                        onchange="javascript:getSerie()">
                                        <option value="--" selected>--</option>
                                        @isset($encargo)
                                                <?php $documento_selected = $encargo->documento_id; ?>
                                        @endisset
                                        @if (count($documento))
                                            @foreach ($documento as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ isset($documento_selected) && $documento_selected == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-3">
                                    <label class="form-label">Serie:</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="documento_serie"
                                            name="documento_serie" value="{{ $encargo->documento_serie }}" disabled />
                                    @else
                                        <input type="text" class="form-control" id="documento_serie"
                                            name="documento_serie" value="" disabled />
                                    @endif
                                </div>
                                <div class="col-xxl-4">
                                    <label class="form-label">Correlativo:</label>
                                    @if (isset($encargo))
                                        <input type="text" class="form-control" id="documento_correlativo"
                                            name="documento_correlativo" value="{{ $encargo->documento_correlativo }}"
                                            disabled />
                                    @else
                                        <input type="text" class="form-control" id="documento_correlativo"
                                            name="documento_correlativo" value="" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-12">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--begin::Separator-->
        <div class="separator border-gray-200 mb-2"></div>
        <!--end::Separator-->

        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <table
                        class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                        <thead class="border-gray-200 fw-bold bg-lighten">
                            <tr>
                                <th scope="col">Descripción</th>
                                <!-- <th scope="col" width="150" style="text-align:right">Peso&nbsp;&nbsp;&nbsp;&nbsp;</th> -->
                                <th scope="col" width="150" style="text-align:right">Cantidad&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th scope="col" width="150" style="text-align:right">Valor
                                    unitario&nbsp;&nbsp;&nbsp;&nbsp;<br></th>
                                <th scope="col" width="150" style="text-align:right">Valor venta&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th scope="col" width="80" style="text-align:right">
                                    <a onclick="javascript:addChargeRow()"><img
                                            src="{{ asset('public/assets/media/plus-circle.svg') }}" width="20" /></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="chargeRow">
                            @if (isset($encargo))
                                @if (!empty($encargo->detalles))
                                    @foreach ($encargo->detalles as $detalle)
                                        <tr>
                                            <td>
                                                <select class="form-select" aria-label="--" name="descripcion"
                                                    onchange="javascript:updateChargeDetail(this)">
                                                    <option value="--" selected> -- </option>
                                                    @if (isset($carga))
                                                        @foreach ($carga as $item)
                                                            <option value="{{ $item->id }}"
                                                                data-amount="{{ $item->valor_unitario }}"
                                                                {{ $detalle['descripcion'] == $item->nombre ? 'selected' : '' }}>
                                                                {{ $item->nombre }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <!-- <td><input type="number" class="form-control fw8" name="peso"
                                                                        onkeyup="javascript:calculatePayChargeDetail(this);"></td>-->
                                            <td><input type="hidden" class="form-control fw8" name="peso"
                                                    value="{{ $detalle['peso'] }}"
                                                    onkeyup="javascript:calculatePayChargeDetail(this);" />
                                                <input type="number" class="form-control fw8" name="cantidad"
                                                    value="{{ $detalle['cantidad_item'] }}"
                                                    onkeyup="javascript:calculatePayChargeDetail(this);" />
                                            </td>
                                            <td><input type="number" class="form-control fw8" name="valor_unitario"
                                                    value="{{ number_format($detalle['valor_unitario'], 2, '.','') }}"
                                                    onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>
                                            <td><input type="number" class="form-control fw8" name="total"
                                                    value="{{ number_format($detalle['valor_venta'], 2, '.', '') }}" disabled></td>
                                        </tr>
                                    @endforeach
                                @endif

                            @else
                                <tr>
                                    <td>
                                        <select class="form-select" aria-label="--" name="descripcion"
                                            onchange="javascript:updateChargeDetail(this)">
                                            <option value="--" selected> -- </option>
                                            @if (isset($carga))
                                                @foreach ($carga as $item)
                                                    <option value="{{ $item->id }}"
                                                        data-amount="{{ $item->valor_unitario }}">
                                                        {{ $item->nombre }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <!-- <td><input type="number" class="form-control fw8" name="peso"
                                                                    onkeyup="javascript:calculatePayChargeDetail(this);"></td>-->
                                    <td><input type="hidden" class="form-control fw8" name="peso"
                                            onkeyup="javascript:calculatePayChargeDetail(this);" value="1">
                                        <input type="number" class="form-control fw8" name="cantidad"
                                            onkeyup="javascript:calculatePayChargeDetail(this);">
                                    </td>
                                    <td><input type="number" class="form-control fw8" name="valor_unitario"
                                            onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>
                                    <td><input type="number" class="form-control fw8" name="total" disabled></td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td align="right">
                                    <!--Descuento global&nbsp;&nbsp;&nbsp;&nbsp;-->
                                </td>
                                <td align="right">
                                    <input type="hidden" name="cantidad_total" value="0">
                                    @if (isset($encargo))
                                        <input type="hidden" class="form-control fw8" name="descuento" value="{{ $encargo->descuento }}" disabled>
                                    @else
                                        <input type="hidden" class="form-control fw8" name="descuento" value="0.00" disabled>
                                    @endif
                                </td>

                                <td align="right">Subtotal + IGV&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                    @if (isset($encargo))
                                        <input type="number" class="form-control fw8" name="subtotal"
                                            value="{{ $encargo->subtotal }}" disabled>
                                    @else
                                        <input type="number" class="form-control fw8" name="subtotal" value="0" disabled>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right">Importe a pagar + IGV&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td>
                                    @if (isset($encargo))
                                        <input type="number" class="form-control fw8" name="importe_pagar_con_descuento"
                                            value="{{ $encargo->oferta }}" disabled>
                                    @else
                                        <input type="number" class="form-control fw8" name="importe_pagar_con_descuento"
                                            value="0.00">
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-2">

                        </div>
                        <div class="col-3">
                            <b>Encomienda:</b> -<br />
                            <b>SUNAT:</b>
                            @if (isset($encargo))
                                <span name="cdr_descripcion">{{ $encargo->cdr_descripcion }}
                                    @if ($encargo->cdr_codigo === '0')
                                        <img src="{{ asset('public/assets/media/check-circle.svg') }}" width="20" />
                                    @else
                                        <img src="{{ asset('public/assets/media/alert-circle.svg') }}" width="20" />
                                    @endif
                                </span>
                            @else
                                <span name="cdr_descripcion">-</span>
                            @endif
                            <br />
                        </div>
                        <!--<div class="col-2">
                            <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-2">
                                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                    <span class="fw-bold text-gray-400">envíos y viajes en el año</span>
                                    <span class="fw-bolder">2</span>
                                </div>
                                <div class="h-5px mx-3 w-100 bg-light mb-3">
                                    <div class="bg-success rounded h-5px" role="progressbar" style="width: 50%;"
                                        aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>-->
                        <div class="col-5 text-end align-top">
                                <a class="btn btn-primary" id="btnConfirmar" onclick="javascript:doit();">Confirmar</a>
                                <a id="btnBuscar" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalBuscarVenta" onclick="javascript:document.getElementById('buscaDocRecibeDocEnvia').focus();">
                                    <img src="{{ asset('public/assets/media/search-white.svg') }}" width="20" />
                                </a>
                                @php
                                    $img_enable = (isset($encargo))?'-white': '';
                                    $btn_enable = (isset($encargo))?'btn-primary': 'btn-secondary disabled';
                                @endphp
                                <a id="btnImprimir" class="btn {{$btn_enable}}" data-bs-toggle="modal"
                                data-bs-target="#modalImprimirComprobante" onclick="javascript:printElement()">
                                    <img src="{{ asset('public/assets/media/printer'.$img_enable.'.svg') }}" width="20" />
                                </a>
                                <a id="btnEmail" class="btn {{$btn_enable}}" data-bs-toggle="modal"
                                data-bs-target="#modalEnviarEmail">
                                    <img src="{{ asset('public/assets/media/email'.$img_enable.'.svg') }}" width="20" />
                                </a>
                                <a id="btnEliminar" class="btn {{$btn_enable}}" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarVenta">
                                    <img src="{{ asset('public/assets/media/trash-2'.$img_enable.'.svg') }}" width="20" />
                                </a>
                                <a href="{{url("/venta/nuevo")}}" class="btn btn-primary" >
                                    Nuevo
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        const factura = '2'; //documento : id para el valor factura
        const otros = '1'; // item: id para el valor otros
        const RUC = 11;
        const DNI = 8;
        const CE = 12;
        const MAX_RESULT_GUIA_REMISION = 3; 

        function sendEmail() {
            alert('enviar correo..');
        }

        function showSuccessToastr(message) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-left",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "10000",
                "hideDuration": "1000",
                "timeOut": "10000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success(message);
        }

        function showErrorToastr(message) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-left",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "10000",
                "hideDuration": "1000",
                "timeOut": "10000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.error(message);
        }

        function showWarningToastr(message) {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-left",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "10000",
                "hideDuration": "1000",
                "timeOut": "10000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.warning(message);
        }

        function updateChargeDetail(element) {
            var tr = $(element).parent().parent();
            var valor_unitario = 0;
            if (element.value === otros) {
                // ABRIR EL valor_unitario PARA *OTROS*
                $(tr).find("td [name='valor_unitario']").removeAttr("disabled");
            } else {
                // los valor_unitarios NO incluyen IGV
                valor_unitario = $("[name='descripcion'] option[value='" + element.value + "']").data("amount");
            }
            $(tr).find("td [name='valor_unitario']").val(valor_unitario);

            var cantidad = $(tr).find("td [name='cantidad']").val();
            if (cantidad === '') {
                $(tr).find("td [name='cantidad']").val(1);
            }
            $(tr).find("td [name='cantidad']").trigger("onkeyup");
        }

        function calculatePayChargeDetail(element) {
            var tr = $(element).parent().parent();
            var valor_unitario = parseFloat($(tr).find("td [name='valor_unitario']").val());
            var cantidad = parseFloat($(tr).find("td [name='cantidad']").val());
            var peso = parseFloat($(tr).find("td [name='peso']").val());
            var total = valor_unitario * cantidad * peso;
            $(tr).find("td [name='total']").val(total.toFixed(2));
            reCalculatePayChargeDetail();
        }

        function reCalculatePayChargeDetail() {
            var subtotal = 0.00;
            var total = 0.00;
            $("#chargeRow >tr>td:nth-child(4)").each(function(index, element) {
                total = parseFloat($(element).find("[name='total']").val());
                subtotal = subtotal + total + (total * parseFloat("{{ env('IGV') }}"));
                $("[name='subtotal']").val(subtotal.toFixed(2));
                $("[name='importe_pagar_con_descuento']").val(subtotal.toFixed(2));
            });
            $("[name='descuento']").val('0.00');
            calculateCantidadTotal()
        }

        function calculateCantidadTotal() {
            var cantidad_total = 0;
            var cantidad = 0;
            $("#chargeRow >tr>td:nth-child(2) [name='cantidad']").each(function(index, element) {
                cantidad = parseInt(element.value)
                if (isNaN(cantidad)) {
                    cantidad = 0
                }
                cantidad_total += cantidad;
                $("[name='cantidad_total']").val(cantidad_total);
            });
        }
        function addChargeRow() {
            var options = '<option value="--" selected> -- </option>';
            @if (isset($carga))
                @foreach ($carga as $item)
                    options +='<option value="{{ $item->id }}" data-amount="{{ $item->valor_unitario }}">';
                        options +='{{ $item->nombre }}'
                        options +='</option>';
                @endforeach
            @endif
            var html = '<tr>' +
                '<td>' +
                '<select class="form-select" aria-label="--" name="descripcion"  onchange="javascript:updateChargeDetail(this)">' +
                options +
                '</select></td>' +
                // '<td><input type="number" class="form-control fw8" name="peso" onkeyup="javascript:calculatePayChargeDetail(this)"></td>' +
                '<td><input type="hidden" class="form-control fw8" name="peso" onkeyup="javascript:calculatePayChargeDetail(this)" value="1">' +
                '<input type="number" class="form-control fw8" name="cantidad" onkeyup="javascript:calculatePayChargeDetail(this)"></td>' +
                '<td><input type="number" class="form-control fw8" name="valor_unitario" onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>' +
                '<td><input type="number" class="form-control fw8" name="total" disabled></td>' +
                '<td scope="row" align="right">' +
                '<a onclick="javascript:removeChargeRow(this)"><img src="{{ asset('public/assets/media/minus-circle.svg') }}" width="20" /></a>' +
                '</td>' +
                '</tr>';
            $("#chargeRow").append(html);
        }

        function removeChargeRow(element) {
            var total = $("#chargeRow").find("tr").length;
            if (total > 1) {
                $(element).parent().parent().remove();
                reCalculatePayChargeDetail();
            } else {
                // al menos debe mantener una fila
            }
        }

        function str_pad(value, length) {
            return (value.toString().length < length) ?
                str_pad("0" + value, length) : value;
        }

        function putChargeForm(data) {
            $("[name='encargo_id']").val(data.id);

            // hacer al receptor un adquiriente
            $("[name='doc_envia']").val(data.doc_recibe); // $("[name='doc_envia']").val(data.doc_envia);
            $("[name='nombre_envia']").val(data.nombre_recibe); // $("[name='nombre_envia']").val(data.nombre_envia);
            $("[name='adquiriente']").val(0);
            $("[name='guia_remision_transportista']").val(1);

            // $("[name='celular_envia']").val(data.celular_envia);
            // $("[name='email_envia']").val(data.email_envia);
            $("[name='fecha_hora_envia']").val("");
            $("[name='doc_recibe']").val(data.doc_recibe);
            $("[name='nombre_recibe']").val(data.nombre_recibe);
            // $("[name='celular_recibe']").val(data.celular_recibe);
            // $("[name='email_recibe']").val(data.email_recibe);

            // F. recepción:
            if (data.fecha_hora_envia) {
                $("[name='fecha_hora_envia']").val(data.fecha_hora_envia); // yyyy-mm-dd hh:ii:ss
                var fecha_hora_envia = (data.fecha_hora_envia).split(' ');
                var fecha_hora_envia_dd_mm_yyyy = fecha_hora_envia[0].slice(8,12)+'-'+fecha_hora_envia[0].slice(5,7)+'-'+fecha_hora_envia[0].slice(0,4)+' '+fecha_hora_envia[1];
                $("[name='fecha_hora_envia_blocked']").val(fecha_hora_envia_dd_mm_yyyy); // dd-mm-yyyy hh:ii:ss
            }

            // F. entrega:
            if (data.fecha_hora_recibe) {
                $("[name='fecha_hora_recibe']").val(data.fecha_hora_recibe); // yyyy-mm-dd hh:ii:ss
                var fecha_hora_recibe = (data.fecha_hora_recibe).split(' ');
                var fecha_hora_recibe_dd_mm_yyyy = fecha_hora_recibe[0].slice(8,12)+'-'+fecha_hora_recibe[0].slice(5,7)+'-'+fecha_hora_recibe[0].slice(0,4)+' '+fecha_hora_recibe[1];
                $("[name='fecha_hora_recibe_blocked']").val(fecha_hora_recibe_dd_mm_yyyy); // dd-mm-yyyy hh:ii:ss
            }
            // $("[name='origen']").val(data.origen).change(); // come from session
            $("[name='agencia_origen']").val(data.agencia_origen); // come from session
            // $("[name='destino']").val(data.destino).change();
            getAgenciaDestino(data.agencia_origen, data.agencia_destino)
            // $("[name='medio_pago']").val(data.medio_pago).change();
            // $("[name='documento']").val(data.documento_id).change();
            // $("[name='documento_serie']").val(data.documento_serie);
            // $("[name='documento_correlativo']").val(data.documento_correlativo);
            
            
            if (data.detalles.length > 0) {
                var total = data.detalles.length;
                _.forEach(data.detalles, function(element, index) {
                    var j = index + 1;
                    $("#chargeRow > tr:nth-child(" + j + ") [name='descripcion']").val(element.item_id).change();
                    $("#chargeRow > tr:nth-child(" + j + ") [name='valor_unitario']").val(element.valor_unitario);
                    $("#chargeRow > tr:nth-child(" + j + ") [name='cantidad']").val(element.cantidad_item).trigger(
                        "onkeyup");
                    // $("#chargeRow > tr:nth-child(" + j + ") [name='peso']").val(element.peso).trigger("onkeyup");
                    if (j < total) {
                        addChargeRow();
                    }
                });
            }

            // set hiddens
            console.log(data)
        }

        function getChargeForm() {
            var encargo_id = $("[name='encargo_id']").val().trim();
            var guia_remision_transportista_id = $("[name='guia_remision_transportista']").val().trim();

            var doc_envia = $("[name='doc_envia']").val().trim();
            var nombre_envia = $("[name='nombre_envia']").val().trim();
            var nombre_comercial_envia = $("[name='nombre_comercial_envia']").val().trim();
            var direccion_envia = $("[name='direccion_envia']").val().trim();
            // var celular_envia = $("[name='celular_envia']").val().trim();
            // var email_envia = $("[name='email_envia']").val().trim();
            var fecha_hora_envia = $("[name='fecha_hora_envia']").val().trim();


            var doc_recibe = $("[name='doc_recibe']").val().trim();
            var nombre_recibe = $("[name='nombre_recibe']").val().trim();
            var nombre_comercial_recibe = $("[name='nombre_comercial_recibe']").val().trim();
            var direccion_recibe = $("[name='direccion_recibe']").val().trim();
            // var celular_recibe = $("[name='celular_recibe']").val().trim();
            // var email_recibe = $("[name='email_recibe']").val().trim();
            var fecha_hora_recibe = $("[name='fecha_hora_recibe']").val().trim();
            
            var doc_recibe_alternativo = $("[name='doc_recibe_alternativo']").val().trim();
            var nombre_recibe_alternativo = $("[name='nombre_recibe_alternativo']").val().trim();
            var nombre_comercial_recibe_alternativo = $("[name='nombre_comercial_recibe_alternativo']").val().trim();
            var direccion_recibe_alternativo = $("[name='direccion_recibe_alternativo']").val().trim()

            // var origen = $("[name='origen']").val().trim();
            var agencia_origen = $("[name='agencia_origen']").val().trim();
            var agencia_destino = $("[name='agencia_destino']").val().trim();
            // var medio_pago = $("[name='medio_pago']").val().trim();
            var adquiriente_id = $("[name='adquiriente']").val().trim();
            var documento_id = $("[name='documento']").val().trim();
            var documento_serie = $("[name='documento_serie']").val().trim();
            var documento_correlativo = $("[name='documento_correlativo']").val().trim();
            var subtotal = $("[name='subtotal']").val().trim();
            var importe_pagar_con_descuento = $("[name='importe_pagar_con_descuento']").val().trim();
            var descuento = $("[name='descuento']").val().trim();

            var descripcion = document.getElementsByName("descripcion");
            var cantidad = document.getElementsByName("cantidad");
            var valor_unitario = document.getElementsByName("valor_unitario");
            var peso = document.getElementsByName("peso");
            var n = descripcion.length;
            var encargo = [];
            for (var i = 0; i < n; i++) {
                encargo.push({
                    'descripcion': descripcion[i].value,
                    'peso': peso[i].value,
                    'cantidad': cantidad[i].value,
                    'valor_unitario': valor_unitario[i].value
                });
            }
            var data = {
                encargo_id: encargo_id,
                guia_remision_transportista_id: guia_remision_transportista_id,
                doc_envia: doc_envia,
                nombre_envia: nombre_envia,
                nombre_comercial_envia: nombre_comercial_envia,
                direccion_envia: direccion_envia,
                // celular_envia: celular_envia,
                // email_envia: email_envia,
                fecha_hora_envia: fecha_hora_envia,

                doc_recibe: doc_recibe,
                nombre_recibe: nombre_recibe,
                nombre_comercial_recibe: nombre_comercial_recibe,
                direccion_recibe: direccion_recibe,
                // celular_recibe: celular_recibe,
                // email_recibe: email_recibe,
                fecha_hora_recibe: fecha_hora_recibe,

                doc_recibe_alternativo: doc_recibe_alternativo,
                nombre_recibe_alternativo: nombre_recibe_alternativo,
                nombre_comercial_recibe_alternativo: nombre_comercial_recibe_alternativo,
                direccion_recibe_alternativo: direccion_recibe_alternativo,

                // data.push("origen", origen);
                agencia_origen: agencia_origen,
                agencia_destino: agencia_destino,
                // medio_pago: medio_pago,
                adquiriente: adquiriente_id,
                documento: documento_id,
                documento_serie: documento_serie,
                documento_correlativo: documento_correlativo,
                subtotal: subtotal,
                importe_pagar_con_descuento: importe_pagar_con_descuento,
                descuento: descuento,
                encargo: encargo,
                
            };
            return data;
        }

        function validate(data) {
            if (data.documento === factura && data.doc_envia.length !== RUC) {
                if(data.doc_envia.length < RUC){
                    showErrorToastr('Al documento <b>' + data.doc_envia + '</b> le faltan ' + (RUC-data.doc_envia.length) +
                    ' caracteres para ser un RUC.');
                } else {
                    showErrorToastr('Al documento <b>' + data.doc_envia + '</b> le sobran ' + (data.doc_envia.length-RUC) +
                    ' caracteres para ser un RUC.');
                }
                $("[name='doc_envia']").focus();
                return false;
            }
            if (data.doc_envia.length !== DNI && data.doc_envia.length !== RUC && data.doc_envia.length !== CE) {
                showErrorToastr('Documento incorrecto (envía),<br>has ingresado ' + data.doc_envia.length + ' caracteres.');
                $("[name='doc_envia']").focus();
                return false;
            }
            if (data.nombre_envia.length === 0) {
                showErrorToastr('Completa los nombre de quien <b>envía</b>.');
                $("[name='nombre_envia']").focus();
                return false;
            }
            if (data.doc_recibe.length !== DNI && data.doc_recibe.length !== RUC && data.doc_recibe
                .length !== CE) {
                showErrorToastr('Documento incorrecto (recibe),<br>has ingresado ' + data.doc_recibe.length + ' caracteres.');
                $("[name='doc_recibe']").focus();
                return false;
            }
            if (data.nombre_recibe.length === 0) {
                showErrorToastr('Completa los nombre de quien <b>recibe</b>.');
                $("[name='nombre_recibe']").focus();
                return false;
            }
            if (data.doc_recibe_alternativo.length>0 && data.doc_recibe_alternativo.length !== DNI && data.doc_recibe_alternativo.length !== RUC && data.doc_recibe_alternativo
                .length !== CE) {
                showErrorToastr('Documento incorrecto (recibe),<br>has ingresado ' + data.doc_recibe_alternativo.length + ' caracteres.');
                $("[name='doc_recibe']").focus();
                return false;
            }
            if (data.doc_recibe_alternativo.length>0 && data.nombre_recibe_alternativo.length === 0) {
                showErrorToastr('Completa los nombre de quien <b>recibe</b>.');
                $("[name='nombre_recibe']").focus();
                return false;
            }
            if (data.agencia_origen.length === 2 && data.agencia_origen === '--') {
                showErrorToastr('Especifica la agencia de origen.');
                return false;
            }
            if (data.agencia_destino.length === 2 && data.agencia_destino === '--') {
                showErrorToastr('Especifica la agencia de destino.');
                return false;
            }
            if (data.documento.length === 2) {
                showErrorToastr('Selecciona una opción: Boleta, Factura o Guía de Remisión.');
                return false;
            }
            if (data.documento_serie.length === 0) {
                showErrorToastr('No se dispone del serie.');
                return false;
            }
            if (parseFloat(data.importe_pagar_con_descuento) > parseFloat(data.subtotal)) {
                showErrorToastr('El importe a pagar no puede ser mayor que total (suma de subtotales).');
                return false;
            }
            return true;
        }

        function getAgenciaDestino(destinoId, selected) {
            var sedeId = $("[name='agencia_origen'] option[value='" + destinoId + "']").data('sede');
            $("[name='agencia_destino']").html("<option value='--'>--</option>");
            if (destinoId !== '--') {
                $.ajax({
                    url: "{{ url('/api/v1/agencia') }}/" + sedeId,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    if (result) {
                        _.forEach(result, function(element, index) {
                            agencia_destino_selected = (selected == element.id) ? "selected" : "";
                            $("[name='agencia_destino']").append("<option value='" + element.id + "' " +
                                agencia_destino_selected + ">" +
                                element.direccion + "</option>");

                        });
                    }
                });
            } else {
                // no hay agencia_destino, me quedo en vacío
            }
        }

        function getSerie() {
            var agencia_origen = $("[name='agencia_origen']").val();
            var agencia_destino = $("[name='agencia_destino']").val();
            var documento_id = $("[name='documento']").val();
            
            if (agencia_origen !== '--' && agencia_destino !== '--' && documento_id !== '--') {
                $.ajax({
                    url: "{{ url('/api/v1/serie') }}/" + agencia_origen + "/" + agencia_destino + "/" + documento_id,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    if (result) {
                        $("[name='documento_serie']").val(result[0].serie);
                    }
                });
            } else {
                // no hay serie para iniciar
                $("[name='documento_serie']").val('');
            }
        }

        function askEncargo() {
            var doc_recibe_envia = $("#buscaDocRecibeDocEnvia").val().trim();
            var documento = $("#buscaDocumento").val().trim();
            if (doc_recibe_envia.length === DNI || doc_recibe_envia.length === RUC || doc_recibe_envia.length === CE || documento.length>0) {
                $.ajax({
                    url: "{{ url('/api/v1/guia-remision-transportista/any') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        doc_recibe_envia: doc_recibe_envia,
                        documento: documento
                    },
                    beforeSend: function () {
                        $("#responseChargeRow").html("");
                        $("#loading-modal").show();
                    }
                }).done(function(response) {
                    $("#loading-modal").hide();
                    var html = '<tr><td colspan="7" align="center">No se encontraron coincidencias</td></tr>';
                    if (response.result.encargo.length > 0) {
                        var i = 0;
                        _.forEach(response.result.encargo, function(element, index) {
                            i++;
                            if(i <= MAX_RESULT_GUIA_REMISION) {
                                html = '<tr>' +
                                    '<td>' + element.doc_envia + '<br>'+ element.nombre_envia +'</td>' +
                                    '<td>' + element.doc_recibe + '<br>'+ element.nombre_recibe +'</td>' +
                                    '<td>' + element.direccion + '</td>' +
                                    '<td>' + element.documento_fecha + '<br>'+ element.documento_hora +'</td>' +
                                    '<td>' + element.oferta + '</td>' +
                                    '<td>' + element.oferta + '</td>' +
                                    '<td><a onclick="loadForm('+element.id+')" type="button" class="btn btn-primary">Pagar</a></td>' +
                                    '</tr>';
                                $("#responseChargeRow").append(html);
                            };
                        });   
                    } else {
                        $("#responseChargeRow").html(html);
                    }
                });
            } else {
                // no hay suficientes valores
            }
        }
        
        function loadForm(encargo_id) {
            $.ajax({
                url: "{{ url('/api/v1/guia-remision-transportista') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    encargo_id: encargo_id
                },
                dataType: "json",
                beforeSend: function() {
                    // $("#btnConfirmar").html(
                    //     '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only">por favor espere</span></div> Guardando'
                    // );
                }
            }).done(function(response) {
                if(response.result.status == 'OK') {
                    putChargeForm(response.result.encargo);
                    enabledBtn();
                    $("#modalBuscarVenta").modal('hide');
                }
            });
        }

        function enabledBtn() {
            $("#btnImprimir").removeClass("disabled btn-secondary");
            $("#btnImprimir").addClass("btn-primary");
            $("#btnEmail").removeClass("disabled btn-secondary");
            $("#btnEmail").addClass("btn-primary");
            $("#btnEliminar").removeClass("disabled btn-secondary");
            $("#btnEliminar").addClass("btn-primary");
            $("#btnImprimir").children().attr("src", "{{ asset('public/assets/media/printer-white.svg') }}");
            $("#btnEmail").children().attr("src", "{{ asset('public/assets/media/email-white.svg') }}");
            $("#btnEliminar").children().attr("src", "{{ asset('public/assets/media/trash-2-white.svg') }}");
        }

        function alertarDetraccion() {
            showWarningToastr('Evita la detracción emitiendo boletas, facturas o guías por montos menores a <b>' +
                '{{ env('DETRACCION') }}</b>');
            var myModal = new bootstrap.Modal(document.getElementById('modalAlertarDetraccion'), {
                // keyboard: false
            })
            myModal.show();
        }

        function enviarDatos(data) {
            if (data == null) {
                data = getChargeForm();
            }
            var irregular = _.find(data.encargo, function(o) { return o.descripcion == '--' || o.cantidad == '' || o.cantidad == 0; })
            if (!irregular) {
                $.ajax({
                    url: "{{ url('/venta/registrar') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        $("#btnConfirmar").html(
                            '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only">por favor espere</span></div> Guardando'
                        );
                    }
                }).done(function(response) {
                    if (response.result.status === 'OK') {
                        if (response.result.encargo_id.toString().length > 0) {
                            $("[name='encargo_id']").val(response.result.encargo_id);
                            $("[name='guia_remision_transportista']").val(response.result.guia_remision_transportista_id);
                            $("[name='adquiriente']").val(response.result.adquiriente_id);
                            
                            // F. recepción:
                            if (response.result.fecha_hora_envia) {
                                $("[name='fecha_hora_envia']").val(response.result.fecha_hora_envia); //yyyy-mm-dd hh:ii:ss
                                var fecha_hora_envia = (response.result.fecha_hora_envia).split(' ');
                                var fecha_hora_envia_dd_mm_yyyy = fecha_hora_envia[0].slice(8,12)+'-'+fecha_hora_envia[0].slice(5,7)+'-'+fecha_hora_envia[0].slice(0,4)+' '+fecha_hora_envia[1];
                                $("[name='fecha_hora_envia_blocked']").val(fecha_hora_envia_dd_mm_yyyy); // dd-mm-yyyy hh:ii:ss
                            }
                            
                            $("[name='documento_correlativo']").val(str_pad(response.result.documento_correlativo,{{ env('ZEROFILL', 8) }}));
                            $("[name='url_documento_pdf']").val(response.result.url_documento_pdf);
                            $("[name='cdr_descripcion']").html(response.result.cdr_descripcion);

                            
                            enabledBtn();
                            showSuccessToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr(response.result.message);
                    }
                    $("#btnConfirmar").html("Confirmar");
                }).fail(function() {
                    showErrorToastr('No se ha podido procesar la venta');
                    $("#btnConfirmar").html("Confirmar");
                });
            } else {
                showErrorToastr('Indica el detalle de la encomienda: descripción, cantidad y valor unitario.');
            }
        }

        function doit() {
            var data = getChargeForm();
            if (validate(data)) {
                // anunciar la DETRACCIÓN 
                if (data.importe_pagar_con_descuento >= parseFloat("{{ env('DETRACCION') }}")) {
                    alertarDetraccion();
                } else {
                    enviarDatos(data);
                }
            }
        }

        function printElement() {
            var encargo_id = $("[name='encargo_id']").val();
            $.ajax({
                url: "{{ url('/api/v1/download/pdf64') }}/" + encargo_id,
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $("#btnEnviarEmail").html(
                        '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only">por favor espere</span></div> Enviando'
                    );
                }
            }).done(function(response) {
                if (response) {
                    $("#comprobantePago").attr("src", 'data:application/pdf;base64,' + response);
                    return true;
                }
            });
        }

        function enviarEmail() {
            var email_adquiriente = $("[name='email_adquiriente']").val();
            var encargo_id = $("[name='encargo_id']").val();
            $.ajax({
                url: "{{ url('/enviar-comprobante') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                data: {
                    email_adquiriente: email_adquiriente,
                    encargo_id: encargo_id
                },
                beforeSend: function() {
                    $("#btnEnviarEmail").html(
                        '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only">por favor espere</span></div> Enviando'
                    );
                }
            }).done(function(response) {
                if (response.result.status === 'OK') {
                    showSuccessToastr(response.result.message);
                } else {
                    showErrorToastr(response.result.message);
                }
                $("#btnEnviarEmail").html('Continuar');
            }).fail(function() {
                $("#btnEnviarEmail").html('Continuar');
                showErrorToastr('No se pudo enviar el e-mail.');
            });
        }

        function bajaCPE() {
            var encargo_id = $("[name='encargo_id']").val();
            $.ajax({
                url: "{{ url('/venta/comunicar-baja') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                data: {
                    encargo_id: [encargo_id]
                },
                beforeSend: function() {
                    $("#btnBajaCPE").html('<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only">por favor espere</span></div> Enviando');
                }
            }).done(function(response) {
                if (response.result.status === 'OK') {
                    showSuccessToastr(response.result.message);
                } else {
                    showErrorToastr(response.result.message);
                }
                $("#btnBajaCPE").html('Sí, Continuar');
            }).fail(function() {
                $("#btnBajaCPE").html('Sí, Continuar');
                showErrorToastr('No se pudo comunicar la baja del comprobante de pago electrónico.');
            });
        }

        function removeReceivesRow() {
            $("#RecibeRow").removeClass('display-show');
            $("#RecibeRow").addClass('display-hide');
            $("#doc_recibe_alternativo").val("");
            $("#nombre_recibe_alternativo").val("");
        }

        function addReceivesRow() {
            $("#RecibeRow").removeClass('display-hide');
            $("#RecibeRow").addClass('display-show');
            // $("#doc_recibe_alternativo").val("");
            // $("#nombre_recibe_alternativo").val("");
        }

        $("[name='doc_envia']").on('keydown', function(e){
            if(e.which == 8) {
                $("[name='nombre_envia']").val("");
                $("[name='nombre_comercial_envia']").val("");
                $("[name='direccion_envia']").val("");
            }
        });

        $("[name='doc_envia']").on('keypress', function(e) {
            $("[name='nombre_envia']").val("");
            $("[name='nombre_comercial_envia']").val("");
            $("[name='direccion_envia']").val("");
            if (e.which == 13) {
                var doc_envia = $("[name='doc_envia']").val().trim();
                if (doc_envia.length === DNI || doc_envia.length === CE) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + doc_envia,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $("#loading-input1").show();
                        }
                    })
                    .done(function(response) {
                        $("#loading-input1").hide();
                        $("[name='nombre_envia']").val(response.result.nombre);
                        $("[name='nombre_comercial_envia']").val(response.result.nombre);
                        $("[name='direccion_envia']").val(response.result.direccion);
                    });

                } else if (doc_envia.length === RUC) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + doc_envia,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $("#loading-input1").show();
                        }
                    }).done(function(response) {
                        $("#loading-input1").hide();
                        $("[name='nombre_envia']").val(response.result.nombre);
                        $("[name='nombre_comercial_envia']").val(response.result.nombre_comercial);
                        $("[name='direccion_envia']").val(response.result.direccion);

                    });
                } else {
                    showErrorToastr("Ha ingresado " + doc_envia.length + " caracteres, corrijalo por favor.");
                    $("[name='doc_envia']").focus();
                }
            }
        });

        $("[name='doc_recibe']").on('keydown', function(e){
            if(e.which == 8) {
                $("[name='nombre_recibe']").val("");
                $("[name='nombre_comercial_recibe']").val("");
                $("[name='direccion_recibe']").val("");
            }
        });

        $("[name='doc_recibe']").on('keypress', function(e) {
            $("[name='nombre_recibe']").val("");
            $("[name='nombre_comercial_recibe']").val("");
            $("[name='direccion_recibe']").val("");
            if (e.which == 13) {
                var doc_recibe = $("[name='doc_recibe']").val().trim();
                $("[name='nombre_comercial_recibe']").val('');
                if (doc_recibe.length === DNI || doc_recibe.length === CE) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + doc_recibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $("#loading-input2").show();
                        }
                    }).done(function(result) {
                        $("#loading-input2").hide();
                        $("[name='nombre_recibe']").val(result.result.nombre);
                        $("[name='nombre_comercial_recibe']").val(result.result.nombre);
                        $("[name='direccion_recibe']").val(result.result.direccion);
                    });

                } else if (doc_recibe.length === RUC) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + doc_recibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $("#loading-input2").show();
                        }
                    }).done(function(result) {
                        $("#loading-input2").hide();
                        $("[name='nombre_recibe']").val(result.result.nombre);
                        $("[name='nombre_comercial_recibe']").val(result.result.nombre);
                        $("[name='direccion_recibe']").val(result.result.direccion);
                    });
                } else {
                    showErrorToastr("Ha ingresado " + doc_recibe.length + " caracteres, complételo por favor.");
                    $("[name='doc_recibe']").focus();
                }
            }
        });

        $("[name='doc_recibe_alternativo']").on('keydown', function(e){
            if(e.which == 8) {
                $("[name='nombre_recibe_alternativo']").val("");
                $("[name='nombre_comercial_recibe_alternativo']").val("");
                $("[name='direccion_recibe_alternativo']").val("");
            }
        });

        $("[name='doc_recibe_alternativo']").on('keypress', function(e) {
            $("[name='nombre_recibe_alternativo']").val("");
            $("[name='nombre_comercial_recibe_alternativo']").val("");
            $("[name='direccion_recibe_alternativo']").val("");
            if (e.which == 13) {
                var doc_recibe_alternativo = $("[name='doc_recibe_alternativo']").val().trim();
                $("[name='nombre_comercial_recibe_alternativo']").val('');
                if (doc_recibe_alternativo.length === DNI || doc_recibe_alternativo.length === CE) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + doc_recibe_alternativo,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_recibe_alternativo']").val(result.result.nombre);
                        $("[name='nombre_comercial_recibe_alternativo']").val(result.result.nombre);
                        $("[name='direccion_recibe_alternativo']").val(result.result.direccion);
                    });

                } else if (doc_recibe_alternativo.length === RUC) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + doc_recibe_alternativo,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_recibe_alternativo']").val(result.result.nombre);
                        $("[name='nombre_comercial_recibe_alternativo']").val(result.result.nombre);
                        $("[name='direccion_recibe_alternativo']").val(result.result.direccion);
                    });
                } else {
                    showErrorToastr("Ha ingresado " + doc_recibe_alternativo.length + " caracteres, complételo por favor.");
                    $("[name='doc_recibe_alternativo']").focus();
                }
            }
        });

        $("#buscaDocRecibeDocEnvia").on('keypress', function(e) {
            if (e.which == 13) {
                askEncargo();
            }
        });

        $("#buscaDocumento").on('keypress', function(e) {
            if (e.which == 13) {
                askEncargo();
            }
        });

        $("[name='importe_pagar_con_descuento']").on('keyup', function(e) {
            var oferta = parseFloat($(this).val());
            var precio_venta = parseFloat($("[name='subtotal']").val());
            var descuento = 0.00;
            reCalculatePayChargeDetailBasedGlobalPrice(oferta);
            
        });
        @if (isset($encargo))
            getAgenciaDestino("{{ $encargo->agencia_origen }}","{{ $encargo->agencia_destino }}");
            // $("[name='cantidad']").trigger('onkeyup');
        @endif

        function reCalculatePayChargeDetailBasedGlobalPrice(oferta) {
            var subtotal = 0.000000;
            var total = (oferta / (1.00 + parseFloat("{{ env('IGV') }}"))).toFixed(6)
            var igv = (oferta - total).toFixed(6)
            var cantidad_total = parseFloat($("[name='cantidad_total']").val());
            if (isNaN(cantidad_total)) {
                return false;
            }
            
            var valor_unitario = (total / cantidad_total).toFixed(6);
            $("#chargeRow >tr").each(function(index, element) {
                var cantidad = parseFloat($(element).find("td [name='cantidad']").val());
                var peso = parseFloat($(element).find("td [name='peso']").val());
                var total = (valor_unitario * cantidad * peso).toFixed(6);
                subtotal = parseFloat(subtotal) + (parseFloat(total) * (1.00 + parseFloat("{{ env('IGV') }}")))
                subtotal = subtotal.toFixed(6);

                $(element).find("td [name='valor_unitario']").val(valor_unitario);
                $(element).find("td [name='total']").val(total);
                $("[name='subtotal']").val(parseFloat(subtotal).toFixed(2));
            });
            if (subtotal > oferta) {
                descuento = (subtotal-oferta).toFixed(2);
                
                $("[name='descuento']").val(descuento);
            }

        }
        $('#doc_envia').focus();
    </script>
@endsection
