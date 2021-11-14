@extends('layout.layout')
@section('content')
    <style>
        .fw8 {
            font-weight: 800 !important;
        }
    </style>
    <form action="{{ url('/venta/registrar') }}" method="POST">
        <input type="hidden" name="encargo_id" value="" />
        <input type="hidden" name="adquiriente" value="" />
        <input type="hidden" name="nombre_comercial_envia" value="" />
        <input type="hidden" name="nombre_comercial_recibe" value="" />
        <input type="hidden" name="direccion_envia" value="" />
        <input type="hidden" name="direccion_recibe" value="" />
        <input type="hidden" name="url_documento" value="" />
        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Envía:</label>
                                    <input class="form-control" id="doc_envia" name="doc_envia" placeholder="">
                                </div>
                                <div class="col-xxl-8">
                                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                                    <input class="form-control" id="nombre_envia" name="nombre_envia" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Celular:</label>
                                    <input class="form-control" id="celular_envia" name="celular_envia" placeholder="">
                                </div>
                                <div class="col-xxl-5">
                                    <label for="exampleDataList" class="form-label">E-mail:</label>
                                    <input class="form-control" id="email_envia" name="email_envia" placeholder="">
                                </div>
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Fecha:</label>
                                    <input class="form-control" id="fecha_envia" name="fecha_envia" placeholder="" disabled>
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
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Recibe:</label>
                                    <input class="form-control" id="doc_recibe" name="doc_recibe" placeholder="">
                                </div>
                                <div class="col-xxl-8">
                                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                                    <input class="form-control" id="nombre_recibe" name="nombre_recibe" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Celular:</label>
                                    <input class="form-control" id="celular_recibe" name="celular_recibe" placeholder="">
                                </div>
                                <div class="col-xxl-5">
                                    <label for="exampleDataList" class="form-label">E-mail:</label>
                                    <input class="form-control" id="email_recibe" name="email_recibe" placeholder="">
                                </div>
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Fecha:</label>
                                    <input class="form-control" id="fecha_recibe" name="fecha_recibe" placeholder=""
                                        disabled>

                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-12">
                                    <label>&nbsp;</label>
                                    <a class="float-end" onclick="javascript:addReceivesRow()">
                                        <img src="http://localhost/dev.enlaces.sis/public/assets/media/plus-circle.svg" width="24" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Origen:</label>
                                    <select class="form-select" aria-label="--" name="agencia_origen"
                                        onchange="javascript:getSerie()">
                                        <option selected> -- </option>
                                        @if (count($agencia_origen))
                                            @foreach ($agencia_origen as $item)
                                                <option value="{{ $item->_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Destino:</label>
                                    <select class="form-select" aria-label="--" name="destino"
                                        onchange="javascript:getAgenciaDestino(this.value, false)">
                                        <option selected> -- </option>
                                        @if (count($sede))
                                            @foreach ($sede as $item)
                                                <option value="{{ $item->_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-6">
                                    <label for="exampleDataList" class="form-label">Agencia:</label>
                                    <select class="form-select" aria-label="--" name="agencia_destino"
                                        onchange="javascript:getSerie()">
                                        <option value="--" selected> -- </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Medio de pago:</label>
                                    <select class="form-select" aria-label="--" id="medio_pago" name="medio_pago">
                                        <option value="--" selected> -- </option>
                                        <option value="1">CRÉDITO</option>
                                        <option value="2">CONTADO</option>
                                    </select>
                                </div>
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Documento:</label>
                                    <select class="form-select" aria-label="--" id="documento" name="documento"
                                        onchange="javascript:getSerie()">
                                        <option value="--" selected>--</option>
                                        @if (count($documento))
                                            @foreach ($documento as $item)
                                                <option value="{{ $item->_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-2">
                                    <label for="exampleDataList" class="form-label">Serie:</label>
                                    <input class="form-control" id="documento_serie" name="documento_serie" value=""
                                        disabled>
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Correlativo:</label>
                                    <input class="form-control" id="documento_correlativo" name="documento_correlativo" value=""
                                        disabled>
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
                                <th scope="col" width="150" style="text-align:right">Precio&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                    
                                </th>
                                <th scope="col" width="150" style="text-align:right">Total&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th scope="col" width="80" style="text-align:right">
                                    <a onclick="javascript:addChargeRow()"><img
                                            src="{{ asset('assets/media/plus-circle.svg') }}" width="24" /></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="chargeRow">
                            <tr>
                                <td>
                                    <select class="form-select" aria-label="--" name="descripcion"
                                        onchange="javascript:updateChargeDetail(this)">
                                        <option value="--" selected> -- </option>
                                        @if(isset($carga))
                                            @foreach ($carga as $item)
                                                <option value="{{ $item->id }}" data-amount="{{ $item->importe_precio }}" >
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
                                <td><input type="number" class="form-control fw8" name="importe"
                                        onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>
                                <td><input type="number" class="form-control fw8" name="total" disabled></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" align="right">Subtotal + IGV&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="number" class="form-control fw8" name="subtotal" disabled></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="right">Importe a pagar + IGV&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="number" class="form-control fw8" name="importe_pagar_con_descuento" value="0.00">
                                    </td>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-2">
                            
                        </div>
                        <div class="col-3">
                            <b>Encomienda:</b> por despachar<br />
                            <b>SUNAT:</b> pendiente<br />
                        </div>
                        <div class="col-2">
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
                        </div>
                        <div class="col-5 text-end align-top">
                            <a id="btnBuscar" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalBuscarVenta" onclick="javascript:$('#buscaDocRecibe').focus()">
                                <img src="{{ asset('assets/media/search-white.svg') }}" width="24" />
                            </a>
                            <a class="btn btn-primary" onclick="javascript:doit();">Confirmar</a>
                            <a id="btnImprimir" class="btn btn-secondary disabled" data-bs-toggle="modal"
                                data-bs-target="#modalImprimirComprobante" onclick="javascript:printElement()">
                                <img src="{{ asset('assets/media/printer.svg') }}" width="24" />
                            </a>
                            <a id="btnEmail" class="btn btn-secondary disabled" data-bs-toggle="modal"
                                data-bs-target="#modalEnviarEmail">
                                <img src="{{ asset('assets/media/email.svg') }}" width="24" />
                            </a>
                            <a id="btnEliminar" class="btn btn-secondary disabled" data-bs-toggle="modal"
                                data-bs-target="#modalEliminarVenta">
                                <img src="{{ asset('assets/media/trash-2.svg') }}" width="24" />
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
        const factura = '617122a2a8c74c6bfc5e36e6';
        const RUC = 11;
        const DNI = 8;
        const CE = 12;
        
        function sendEmail() {
            alert('enviando correo..');
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

        function updateChargeDetail(element) {
            var tr = $(element).parent().parent();
            var importe = 0;
            if (element.value === '6175bf2e8f1a2809dcb499c9') {
                // ABRIR EL importe PARA *OTROS*
                $(tr).find("td [name='importe']").removeAttr("disabled");
            } else {
                // los importes NO incluyen IGV
                importe = $("[name='descripcion'] option[value='" + element.value + "']").data("amount");
            }
            $(tr).find("td [name='importe']").val(importe);

            var cantidad = $(tr).find("td [name='cantidad']").val();
            if (cantidad === '') {
                $(tr).find("td [name='cantidad']").val(1);
            }
            $(tr).find("td [name='cantidad']").trigger("onkeyup");
        }

        function calculatePayChargeDetail(element) {
            var tr = $(element).parent().parent();
            var importe = parseFloat($(tr).find("td [name='importe']").val());
            var cantidad = parseFloat($(tr).find("td [name='cantidad']").val());
            var peso = parseFloat($(tr).find("td [name='peso']").val());
            var total = importe * cantidad * peso;
            $(tr).find("td [name='total']").val(total.toFixed(2));
            reCalculatePayChargeDetail();
        }

        function reCalculatePayChargeDetail() {
            subtotal = 0.00;
            $("#chargeRow >tr>td:nth-child(4)").each(function(index, element) {
                total = parseFloat($(element).find("[name='total']").val());
                subtotal = subtotal + total + (total*parseFloat("{{ env('IGV') }}"));
                $("[name='subtotal']").val(subtotal.toFixed(2));
                $("[name='importe_pagar_con_descuento']").val(subtotal.toFixed(2));
            });
        }

        function addChargeRow() {
            var options = '<option value="--" selected> -- </option>';
            @if (isset($carga))
                @foreach ($carga as $item)
                    options +='<option value="{{ $item->id }}" data-amount="{{ $item->importe_precio }}" >';
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
                '<td><input type="number" class="form-control fw8" name="importe" onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>' +
                '<td><input type="number" class="form-control fw8" name="total" disabled></td>' +
                '<td scope="row" align="right">' +
                '<a onclick="javascript:removeChargeRow(this)"><img src="{{ asset('assets/media/minus-circle.svg') }}" width="24" /></a>' +
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
            $("[name='encargo_id']").val(data._id);

            $("[name='doc_envia']").val(data.doc_envia);
            $("[name='nombre_envia']").val(data.nombre_envia);
            $("[name='celular_envia']").val(data.celular_envia);
            $("[name='email_envia']").val(data.email_envia);
            $("[name='fecha_envia']").val(data.fecha_envia);
            $("[name='fecha_envia_blocked']").val(data.fecha_envia);

            $("[name='doc_recibe']").val(data.doc_recibe);
            $("[name='nombre_recibe']").val(data.nombre_recibe);
            $("[name='celular_recibe']").val(data.celular_recibe);
            $("[name='email_recibe']").val(data.email_recibe);
            $("[name='fecha_recibe']").val(data.fecha_recibe);
            $("[name='fecha_recibe_blocked']").val(data.fecha_recibe);

            // $("[name='origen']").val(data.origen).change(); // come from session
            $("[name='agencia_origen']").val(data.agencia_origen); // come from session
            $("[name='destino']").val(data.destino).change();
            getAgenciaDestino(data.destino, data.agencia_destino);

            $("[name='medio_pago']").val(data.medio_pago).change();
            $("[name='documento']").val(data.documento).change();
            $("[name='documento_serie']").val(data.documento_serie);
            $("[name='documento_correlativo']").val(data.documento_correlativo);

            $("[name='adquiriente']").val(data.adquiriente);

            if (data.encargo.length > 0) {
                var total = data.encargo.length;
                _.forEach(data.encargo, function(element, index) {
                    var j = index + 1;
                    $("#chargeRow > tr:nth-child(" + j + ") [name='descripcion']").val(element.carga).change();
                    $("#chargeRow > tr:nth-child(" + j + ") [name='importe']").val(element.importe);
                    $("#chargeRow > tr:nth-child(" + j + ") [name='cantidad']").val(element.cantidad).trigger(
                        "onkeyup");
                    // $("#chargeRow > tr:nth-child(" + j + ") [name='peso']").val(element.peso).trigger("onkeyup");
                    if (j < total) {
                        addChargeRow();
                    }
                });
            }
        }

        function getChargeForm() {
            var encargo_id = $("[name='encargo_id']").val().trim();

            var doc_envia = $("[name='doc_envia']").val().trim();
            var nombre_envia = $("[name='nombre_envia']").val().trim();
            var nombre_comercial_envia = $("[name='nombre_comercial_envia']").val().trim();
            var direccion_envia = $("[name='direccion_envia']").val().trim();
            var celular_envia = $("[name='celular_envia']").val().trim();
            var email_envia = $("[name='email_envia']").val().trim();
            var fecha_envia = $("[name='fecha_envia']").val().trim();

            var doc_recibe = $("[name='doc_recibe']").val().trim();
            var nombre_recibe = $("[name='nombre_recibe']").val().trim();
            var nombre_comercial_recibe = $("[name='nombre_comercial_recibe']").val().trim();
            var direccion_recibe = $("[name='direccion_recibe']").val().trim();
            var celular_recibe = $("[name='celular_recibe']").val().trim();
            var email_recibe = $("[name='email_recibe']").val().trim();
            var fecha_recibe = $("[name='fecha_recibe']").val().trim();

            // var origen = $("[name='origen']").val().trim();
            var destino = $("[name='destino']").val().trim();
            var agencia_origen = $("[name='agencia_origen']").val().trim();
            var agencia_destino = $("[name='agencia_destino']").val().trim();
            var medio_pago = $("[name='medio_pago']").val().trim();
            var adquiriente = $("[name='adquiriente']").val().trim();
            var documento = $("[name='documento']").val().trim();
            var documento_serie = $("[name='documento_serie']").val().trim();
            var documento_correlativo = $("[name='documento_correlativo']").val().trim();
            var subtotal = $("[name='subtotal']").val().trim();
            var importe_pagar_con_descuento = $("[name='importe_pagar_con_descuento']").val().trim();

            var descripcion = document.getElementsByName("descripcion");
            var cantidad = document.getElementsByName("cantidad");
            var importe = document.getElementsByName("importe");
            var peso = document.getElementsByName("peso");
            var n = descripcion.length;
            var encargo = [];
            for (var i = 0; i < n; i++) {
                encargo.push({
                    'descripcion': descripcion[i].value, 
                    'peso': peso[i].value, 
                    'cantidad': cantidad[i].value, 
                    'importe': importe[i].value
                    });
            }
            var data = {
                encargo_id: encargo_id,
                doc_envia: doc_envia,
                nombre_envia: nombre_envia,
                nombre_comercial_envia: nombre_comercial_envia,
                direccion_envia: direccion_envia,
                celular_envia: celular_envia,
                email_envia: email_envia,
                fecha_envia: fecha_envia,

                doc_recibe: doc_recibe,
                nombre_recibe: nombre_recibe,
                nombre_comercial_recibe: nombre_comercial_recibe,
                direccion_recibe: direccion_recibe,
                celular_recibe: celular_recibe,
                email_recibe: email_recibe,
                fecha_recibe: fecha_recibe,

                // data.push("origen", origen);
                destino: destino,
                agencia_origen: agencia_origen,
                agencia_destino: agencia_destino,
                medio_pago: medio_pago,
                adquiriente: adquiriente,
                documento: documento,
                documento_serie: documento_serie,
                documento_correlativo: documento_correlativo,
                subtotal: subtotal,
                importe_pagar_con_descuento: importe_pagar_con_descuento,
                encargo: encargo
            };
            return data;
        }

        function validate(data) {
            if (data.doc_envia.length !== DNI && data.doc_envia.length !== RUC && data.doc_envia.length !== CE) {
                showErrorToastr('Documento incorrecto,<br>has ingresado '+data.doc_envia.length+' caracteres.');
                return false;
            }
            if (data.nombre_envia.length === 0) {
                showErrorToastr('Completa los nombre de quien Envía.');
                return false;
            }
            if (data.doc_recibe.length !== DNI && data.doc_recibe.length !== RUC && data.doc_recibe
                .length !== CE) {
                showErrorToastr('Documento incorrecto,<br>has ingresado '+data.doc_recibe.length+' caracteres.');
                return false;
            }
            if (data.nombre_recibe.length === 0) {
                showErrorToastr('Completa los nombre de quien Recibe.');
                return false;
            }
            if (data.agencia_origen.length === 2 && data.agencia_origen === '--') {
                showErrorToastr('Especifica la agencia de origen.');
                return false;
            }
            if (data.destino.length === 2 && data.destino === '--') {
                showErrorToastr('Especifica la agencia de destino.');
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
            if (data.documento === factura && data.doc_envia.length !== RUC) {
                showErrorToastr('Al documento '+data.documento+' le faltan '+ (data.documento.length-RUC)+' caracters para ser un RUC.');
                return false;
            }
            if (parseFloat(data.importe_pagar_con_descuento) > parseFloat(data.subtotal)) {
                showErrorToastr('El importe a pagar no puede ser mayor que total (suma de subtotales).');
                return false;
            }
            return true;
        }

        /*
         * @destinoId : id de la sede de destino
         * @selected : id de la agencia de destino
         */
        function getAgenciaDestino(destinoId, selected) {
            $("[name='agencia_destino']").html("<option value='--'>--</option>");
            if (destinoId !== '--') {
                $.ajax({
                    url: "{{ url('/api/v1/agencia') }}/" + destinoId,
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
                            $("[name='agencia_destino']").append("<option value='" + element._id + "'>" +
                                element.direccion + "</option>");
                        });
                        if (selected) {
                            $("[name='agencia_destino']").val(selected).change();
                        }
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
                    url: "{{ url('/api/v1/serie') }}/" + agencia_origen + "/" + agencia_destino + "/" +
                        documento_id,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    if (result) {
                        var documento_serie = result[0].correlativo;
                        $("[name='documento_serie']").val(documento_serie);
                        // var documento_serie = str_pad(result[0].correlativo, 3);
                    }
                });
            } else {
                // no hay serie para iniciar
                $("[name='documento_serie']").val('');
            }
        }

        function askEncargo() {
            var doc_recibe_envia = $("#buscaDocRecibeDocEnvia").val();
            var documento = $("#buscaDocumento").val();

            if (doc_recibe_envia.length === DNI || doc_recibe_envia.length === RUC || doc_recibe_envia.length === CE) {
                $.ajax({
                    url: "{{ url('/api/v1/encargo') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        doc_recibe_envia: doc_recibe_envia,
                        documento: documento
                    }
                }).done(function(result) {
                    var html = '<tr><td colspan="5">No se encontraron coincidencias</td></tr>';
                    if (result) {
                        _.forEach(result.result.encargo, function(element, index) {
                            html = '<tr>' +
                                '<th scope="row">' +
                                '<input class="form-check-input h-20px w-20px" type="checkbox" name="communication[]" value="email" checked="checked">' +
                                '</th>' +
                                '<td>' + element.doc_envia + '</td>' +
                                '<td>' + element.doc_recibe + '</td>' +
                                '<td>' + element.agencia_destino + '</td>' +
                                '<td>' + element.documento_fecha + '</td>' +
                                '<td>' + element.importe_pagar + '</td>' +
                                '</tr>';
                            $("#responseChargeRow").html(html);
                            putChargeForm(element);
                            enabledBtn();
                        });
                    } else {
                        $("#responseChargeRow").html(html);
                    }
                });
            } else {
                // no hay suficientes valores

            }
        }

        function enabledBtn() {
            $("#btnImprimir").removeClass("disabled btn-secondary");
            $("#btnImprimir").addClass("btn-primary");
            $("#btnEmail").removeClass("disabled btn-secondary");
            $("#btnEmail").addClass("btn-primary");
            $("#btnEliminar").removeClass("disabled btn-secondary");
            $("#btnEliminar").addClass("btn-primary");
            $("#btnImprimir").children().attr("src", "{{ asset('assets/media/printer-white.svg') }}");
            $("#btnEmail").children().attr("src", "{{ asset('assets/media/email-white.svg') }}");
            $("#btnEliminar").children().attr("src", "{{ asset('assets/media/trash-2-white.svg') }}");

        }

        function doit() {
            var data = getChargeForm();
            if (validate(data)) {
                // evitar la DETRACCIÓN 
                /*if (data.importe_pagar_con_descuento >= parseFloat("{{ env('DETRACCION') }}")) {
                    showErrorToastr('Usted debe emitir boletas, facturas o guías por montos menores a ' +
                        '{{ env('DETRACCION') }}');
                    return false;
                }*/
                $.ajax({
                    url: "{{ url('/venta/registrar') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    dataType: "json"
                }).done(function(response) {
                    if (response.result.status === 'OK') {
                        if (data.encargo_id.length === 0) {
                            $("[name='encargo_id']").val(response.result.encargo_id);
                            $("[name='adquiriente']").val(response.result.adquiriente);
                            $("[name='fecha_envia']").val(response.result.fecha_envia);
                            $("[name='documento_correlativo']").val(str_pad(response.result.documento_correlativo,{{ env('ZEROFILL', 8) }}));
                            $("[name='url_documento']").val(response.result.url_documento);
                            enabledBtn();
                            showSuccessToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr(response.result.message);
                    }
                });
                
            }
        }

        function printElement() {
            var url_documento = $("[name='url_documento']").val();
            if (url_documento) {
                $("#comprobantePago").attr("src", url_documento);
                return true;
            }
        }

        $("[name='doc_envia']").on('keypress', function(e) {
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
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_envia']").val(result.result.nombre);
                    });

                } else if (doc_envia.length === RUC) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + doc_envia,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_envia']").val(result.result.nombre);
                        $("[name='nombre_comercial_envia']").val(result.result.nombre_comercial);
                        
                    });
                } else {
                    showErrorToastr("Ha ingresado " + doc_envia.length + " caracteres, complételo por favor.");
                }
            }
        });

        $("[name='doc_recibe']").on('keypress', function(e) {
            if (e.which == 13) {
                var doc_recibe = $("[name='doc_recibe']").val().trim();
                if (doc_recibe.length === DNI || doc_recibe.length === CE) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + doc_recibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_recibe']").val(result.result.nombre);
                    });

                } else if (doc_recibe.length === RUC) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + doc_recibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombre_recibe']").val(result.result.nombre);
                        $("[name='nombre_comercial_recibe']").val(result.result.nombre_comercial);
                    });
                } else {
                    showErrorToastr("Ha ingresado " + doc_recibe.length + " caracteres, complételo por favor.");
                }
            }
        });
    </script>
@endsection
