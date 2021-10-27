@extends('layout.layout')
@section('content')
    <form action="{{ url('/venta/registrar') }}" method="POST">
        <input type="hidden" name="encargoId" value="" />
        <input type="hidden" name="clienteId" value="" />
        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Envía:</label>
                                    <input class="form-control" id="docEnvia" name="docEnvia" placeholder="">
                                </div>
                                <div class="col-xxl-9">
                                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                                    <input class="form-control" id="nombreEnvia" name="nombreEnvia" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Celular:</label>
                                    <input class="form-control" id="celularEnvia" name="celularEnvia" placeholder="">
                                </div>
                                <div class="col-xxl-6">
                                    <label for="exampleDataList" class="form-label">E-mail:</label>
                                    <input class="form-control" id="emailEnvia" name="emailEnvia" placeholder="">
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Fecha:</label>
                                    <input class="form-control" id="fechaEnvia" name="fechaEnvia" placeholder="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-2">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Recibe:</label>
                                    <input class="form-control" id="docRecibe" name="docRecibe" placeholder="">
                                </div>
                                <div class="col-xxl-9">
                                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                                    <input class="form-control" id="nombreRecibe" name="nombreRecibe" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Celular:</label>
                                    <input class="form-control" id="celularRecibe" name="celularRecibe" placeholder="">
                                </div>
                                <div class="col-xxl-6">
                                    <label for="exampleDataList" class="form-label">E-mail:</label>
                                    <input class="form-control" id="emailRecibe" name="emailRecibe" placeholder="">
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Fecha:</label>
                                    <input class="form-control" id="fechaRecibe" name="fechaRecibe" placeholder=""
                                        disabled>

                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-2">
                                    <label>&nbsp;</label>
                                    <a onclick="javascript:addReceivesRow()"><img
                                            src="http://localhost/dev.enlaces.sis/public/assets/media/icons/sis/plus-circle.svg"
                                            width="24"></a>
                                    <br />
                                    <!--<a onclick="javascript:removeReceivesRow(this)"><img
                                                                                    src="http://localhost/dev.enlaces.sis/public/assets/media/icons/sis/x-circle.svg"
                                                                                    width="24"></a>-->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-5">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Origen:</label>
                                    <select class="form-select" aria-label="--" name="agenciaOrigen"
                                        onchange="javascript:getSerie()">
                                        <option selected> -- </option>
                                        @if (count($agenciaOrigen))
                                            @foreach ($agenciaOrigen as $item)
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
                                    <select class="form-select" aria-label="--" name="agenciaDestino"
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
                                    <select class="form-select" aria-label="--" id="medioPago" name="medioPago">
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
                                    <input class="form-control" id="documentoSerie" name="documentoSerie" value=""
                                        disabled>
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Número:</label>
                                    <input class="form-control" id="documentoNumero" name="documentoNumero" value=""
                                        disabled>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-1">
                            <div class="row gy-5">
                                <div class="col-xxl-2">

                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>

        <!--begin::Separator-->
        <div class="separator border-gray-200 mb-6"></div>
        <!--end::Separator-->

        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <table
                        class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                        <thead class="border-gray-200 fw-bold bg-lighten">
                            <tr>
                                <th scope="col">Descripción</th>
                                <th scope="col" width="150" style="text-align:right">Peso&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th scope="col" width="150" style="text-align:right">Cantidad&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th scope="col" width="150" style="text-align:right">Precio unitario&nbsp;&nbsp;&nbsp;&nbsp;
                                </th>
                                <th scope="col" width="150" style="text-align:right">Subtotal&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th scope="col" width="80" style="text-align:right">
                                    <a onclick="javascript:addChargeRow()"><img
                                            src="{{ asset('assets/media/icons/sis/plus-circle.svg') }}" width="24" /></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="chargeRow">
                            <tr>
                                <td>
                                    <select class="form-select" aria-label="--" name="descripcion"
                                        onchange="javascript:updateChargeDetail(this)">
                                        <option value="--" selected> -- </option>
                                        @if (isset($carga))
                                            @foreach ($carga as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->precio }}">
                                                    {{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="peso"
                                        onkeyup="javascript:calculatePayChargeDetail(this);"></td>
                                <td><input type="number" class="form-control" name="cantidad"
                                        onkeyup="javascript:calculatePayChargeDetail(this);"></td>
                                <td><input type="number" class="form-control" name="precio"
                                        onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>
                                <td><input type="number" class="form-control" name="total" disabled></td>
                                <!--<td scope="row" width="80" class="float-right">
                                                                            <a onclick="javascript:removeChargeRow(this)"><img
                                                                                    src="{{ asset('assets/media/icons/sis/x-circle.svg') }}" width="24" /></a>
                                                                        </td>-->
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" align="right">Total&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="number" class="form-control" name="subtotal" disabled></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">Importe a pagar&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="number" class="form-control" name="descuento" value="0.00"></td>
                            </tr>
                        </tfoot>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-3">
                            <b>Conductor:</b> Gerson Sánchez Aguilar<br />
                            <b>Partida:</b> 08:00 PM
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
                        <div class="col-4 text-end align-top">
                            <a id="btnBuscar" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalBuscarVenta" onclick="javascript:$('#buscaDocRecibe').focus()">
                                <img src="{{ asset('assets/media/icons/sis/search-white.svg') }}" width="24" />
                            </a>
                            <a class="btn btn-primary" onclick="javascript:doit();">Confirmar</a>
                            <a id="btnImprimir" class="btn btn-secondary disabled" data-bs-toggle="modal"
                                data-bs-target="#modalImprimirComprobante" onclick="javascript:printElement()">
                                <img src="{{ asset('assets/media/icons/sis/printer.svg') }}" width="24" />
                            </a>
                            <a id="btnEliminar" class="btn btn-secondary disabled" data-bs-toggle="modal"
                                data-bs-target="#modalEliminarVenta">
                                <img src="{{ asset('assets/media/icons/sis/trash-2.svg') }}" width="24" />
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
        function showSuccessToastr(message) {
            console.log('mostrar success toastr')
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "30000",
                "hideDuration": "1000",
                "timeOut": "60000",
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
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "30000",
                "hideDuration": "1000",
                "timeOut": "60000",
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
            var precio = '';
            if (element.value === '6175bf2e8f1a2809dcb499c9') {
                // ABRIR EL PRECIO PARA *OTROS*
                $(tr).find("td [name='precio']").removeAttr("disabled");
            } else {
                precio = $("[name='descripcion'] option[value='" + element.value + "']").data("price");
            }
            $(tr).find("td [name='precio']").val(precio);
            var cantidad = $(tr).find("td [name='cantidad']").val();
            if (cantidad === '') {
                $(tr).find("td [name='cantidad']").val(1);
            }
            $(tr).find("td [name='cantidad']").trigger("onkeyup");
        }

        function calculatePayChargeDetail(element) {
            var tr = $(element).parent().parent();
            var precio = parseFloat($(tr).find("td [name='precio']").val());
            var cantidad = parseFloat($(tr).find("td [name='cantidad']").val());
            var peso = parseFloat($(tr).find("td [name='peso']").val());
            var total = precio * cantidad * peso;
            $(tr).find("td [name='total']").val(total.toFixed(2));
            reCalculatePayChargeDetail();
        }

        function reCalculatePayChargeDetail() {
            subtotal = 0.00;
            $("#chargeRow >tr>td:nth-child(5)").each(function(index, element) {
                subtotal = subtotal + parseFloat($(element).find("[name='total']").val());
                $("[name='subtotal']").val(subtotal.toFixed(2));
                $("[name='descuento']").val(subtotal.toFixed(2));
            });
        }

        function addChargeRow() {
            var options = '<option value="--" selected> -- </option>';
            @if (isset($carga))
                @foreach ($carga as $item)
                    options +='<option value="{{ $item->id }}" data-price="{{ $item->precio }}">';
                        options +='{{ $item->nombre }}'
                        options +='</option>';
                @endforeach
            @endif
            var html = '<tr>' +
                '<td>' +
                '<select class="form-select" aria-label="--" name="descripcion"  onchange="javascript:updateChargeDetail(this)">' +
                options +
                '</select></td>' +
                '<td><input type="number" class="form-control" name="peso" onkeyup="javascript:calculatePayChargeDetail(this)"></td>' +
                '<td><input type="number" class="form-control" name="cantidad" onkeyup="javascript:calculatePayChargeDetail(this)"></td>' +
                '<td><input type="number" class="form-control" name="precio" onkeyup="javascript:calculatePayChargeDetail(this)" disabled></td>' +
                '<td><input type="number" class="form-control" name="total" disabled></td>' +
                '<td scope="row" align="right">' +
                '<a onclick="javascript:removeChargeRow(this)"><img src="{{ asset('assets/media/icons/sis/x-circle.svg') }}" width="24" /></a>' +
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
            $("[name='encargoId']").val(data._id);

            $("[name='docEnvia']").val(data.doc_envia);
            $("[name='nombreEnvia']").val(data.nombre_envia);
            $("[name='celularEnvia']").val(data.celular_envia);
            $("[name='emailEnvia']").val(data.email_envia);
            $("[name='fechaEnvia']").val(data.fecha_envia);
            $("[name='fechaEnviaBlocked']").val(data.fecha_envia);

            $("[name='docRecibe']").val(data.doc_recibe);
            $("[name='nombreRecibe']").val(data.nombre_recibe);
            $("[name='celularRecibe']").val(data.celular_recibe);
            $("[name='emailRecibe']").val(data.email_recibe);
            $("[name='fechaRecibe']").val(data.fecha_recibe);
            $("[name='fechaRecibeBlocked']").val(data.fecha_recibe);

            // $("[name='origen']").val(data.origen).change(); // come from session
            $("[name='agenciaOrigen']").val(data.agencia_origen); // come from session
            $("[name='destino']").val(data.destino).change();
            console.log(data.destino, data.agencia_destino);
            getAgenciaDestino(data.destino, data.agencia_destino);

            $("[name='medioPago']").val(data.medio_pago).change();
            $("[name='documento']").val(data.documento_id).change();
            $("[name='documentoSerie']").val(data.documento_serie);
            // $("[name='documentoNumero']").val(data.documento_numero);

            $("[name='clienteId']").val(data.cliente_id);
        }

        function getChargeForm() {
            var encargoId = $("[name='encargoId']").val().trim();

            var docEnvia = $("[name='docEnvia']").val().trim();
            var nombreEnvia = $("[name='nombreEnvia']").val().trim();
            var celularEnvia = $("[name='celularEnvia']").val().trim();
            var emailEnvia = $("[name='emailEnvia']").val().trim();
            var fechaEnvia = $("[name='fechaEnvia']").val().trim();

            var docRecibe = $("[name='docRecibe']").val().trim();
            var nombreRecibe = $("[name='nombreRecibe']").val().trim();
            var celularRecibe = $("[name='celularRecibe']").val().trim();
            var emailRecibe = $("[name='emailRecibe']").val().trim();
            var fechaRecibe = $("[name='fechaRecibe']").val().trim();

            // var origen = $("[name='origen']").val().trim();
            var destino = $("[name='destino']").val().trim();
            var agenciaOrigen = $("[name='agenciaOrigen']").val().trim();
            var agenciaDestino = $("[name='agenciaDestino']").val().trim();
            var medioPago = $("[name='medioPago']").val().trim();
            var documento = $("[name='documento']").val().trim();
            var documentoSerie = $("[name='documentoSerie']").val().trim();
            var documentoNumero = $("[name='documentoNumero']").val().trim();

            var descripcion = document.getElementsByName("descripcion");
            var cantidad = document.getElementsByName("cantidad");
            var precio = document.getElementsByName("precio");
            var peso = document.getElementsByName("peso");

            var clienteId = $("[name='clienteId']").val().trim();

            var data = new FormData();
            data.append("encargoId", encargoId);
            data.append("docEnvia", docEnvia);
            data.append("nombreEnvia", nombreEnvia);
            data.append("celularEnvia", celularEnvia);
            data.append("emailEnvia", emailEnvia);
            data.append("fechaEnvia", fechaEnvia);
            data.append("docRecibe", docRecibe);
            data.append("nombreRecibe", nombreRecibe);
            data.append("celularRecibe", celularRecibe);
            data.append("emailRecibe", emailRecibe);
            data.append("fechaRecibe", fechaRecibe);
            // data.append("origen", origen);
            data.append("destino", destino);
            data.append("agenciaOrigen", agenciaOrigen);
            data.append("agenciaDestino", agenciaDestino);
            data.append("medioPago", medioPago);
            data.append("documento", documento);
            data.append("documentoSerie", documentoSerie);
            data.append("documentoNumero", documentoNumero);
            var n = descripcion.length;
            for (var i = 0; i < n; i++) {
                data.append("encargo[]", [descripcion[i].value, peso[i].value, cantidad[i].value, precio[i].value]);
            };
            data.append("clienteId", clienteId);
            return data;
        }

        function validate(data) {
            if (data.get('docEnvia').length !== 8 && data.get('docEnvia').length !== 11) {
                alert('Documento incorrecto de quien Envía');
                return false;
            }
            if (data.get('nombreEnvia').length === 0) {
                alert('No se dispone de los nombre de quien Envía');
                return false;
            }
            if (data.get('docRecibe').length !== 8 && data.get('docRecibe').length !== 11) {
                alert('Documento incorrecto de quien Recibe');
                return false;
            }
            if (data.get('nombreRecibe').length === 0) {
                alert('No se dispone de los nombre de quien Recibe');
                return false;
            }
            if (data.get('agenciaOrigen').length === 2 && data.get('agenciaOrigen') === '--') {
                alert('No se dispone del agenciaOrigen');
                return false;
            }
            if (data.get('destino').length === 2 && data.get('destino') === '--') {
                alert('No se dispone del destino');
                return false;
            }
            if (data.get('agenciaDestino').length === 2 && data.get('agenciaDestino') === '--') {
                alert('No se dispone del agencia destino');
                return false;
            }
            if (data.get('documento').length === 2) {
                alert('No se dispone del documento');
                return false;
            }
            if (data.get('documentoSerie').length === 0) {
                alert('No se dispone del serie');
                return false;
            }
            return true;
        }

        /*
         * @destinoId : id de la sede de destino
         * @selected : id de la agencia de destino
         */
        function getAgenciaDestino(destinoId, selected) {
            var agenciaDestino = "<option value='--'>--</option>";
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
                        result.forEach(function(element, index) {
                            agenciaDestino = agenciaDestino + "<option value='" + element._id + "'>" +
                                element.direccion +
                                "</option>";
                        })
                    }
                    $("[name='agenciaDestino']").html(agenciaDestino);
                    if (selected) {
                        $("[name='agenciaDestino']").val(selected).change();
                    }
                });
            } else {
                // no hay agenciaDestino, déjame en vacío
                $("[name='agenciaDestino']").html(agenciaDestino);
            }
        }

        function getSerie() {
            var agenciaOrigen = $("[name='agenciaOrigen']").val();
            var agenciaDestino = $("[name='agenciaDestino']").val();
            var documentoId = $("[name='documento']").val();
            if (agenciaOrigen !== '--' && agenciaDestino !== '--' && documentoId !== '--') {
                $.ajax({
                    url: "{{ url('/api/v1/serie') }}/" + agenciaOrigen + "/" + agenciaDestino + "/" +
                        documentoId,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    console.log('result_getSerie:', result);
                    if (result) {
                        var documentoSerie = result[0].correlativo;
                        $("[name='documentoSerie']").val(documentoSerie);
                        // var documentoSerie = str_pad(result[0].correlativo, 3);
                    }
                });
            } else {
                // no hay serie para iniciar
                $("[name='documentoSerie']").val('');
            }
        }

        function askEncargo() {
            var docRecibeOenvia = $("#buscaDocRecibeDocEnvia").val();
            var documento = $("#buscaDocumento").val();

            if (docRecibeOenvia.length === 8 || docRecibeOenvia.length === 11) {
                $.ajax({
                    url: "{{ url('/api/v1/encargo') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        docRecibeOenvia: docRecibeOenvia,
                        documento: documento
                    }
                }).done(function(result) {
                    var html = '<tr><td colspan="5">No se encontraron coincidencias</td></tr>';
                    if (result) {
                        result.result.encargo.forEach(function(element, index) {
                            html = '<tr>' +
                                '<th scope="row">' +
                                '<input class="form-check-input h-20px w-20px" type="checkbox" name="communication[]" value="email" checked="checked">' +
                                '</th>' +
                                '<td>' + element.doc_envia + '</td>' +
                                '<td>' + element.doc_recibe + '</td>' +
                                '<td>' + element.agencia_destino + '</td>' +
                                '<td>' + element.documento_fecha + '</td>' +
                                '<td>00.00</td>' +
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
            $("#btnEliminar").removeClass("disabled btn-secondary");
            $("#btnEliminar").addClass("btn-primary");

            $("#btnImprimir").children().attr("src", "{{ asset('assets/media/icons/sis/printer-white.svg') }}");
            $("#btnEliminar").children().attr("src", "{{ asset('assets/media/icons/sis/trash-2-white.svg') }}");

        }

        function doit() {
            var data = getChargeForm();
            if (validate(data)) {
                if (subtotal <= parseFloat("{{env('DETRACCION')}}")) {
                    $.ajax({
                        url: "{{ url('/venta/registrar') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: data,
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done(function(result) {
                        if(result.result.status === 'OK'){
                            if (data.get('encargoId').length === 0) {
                                $("[name='encargoId']").val(result.result.encargoId);
                                $("[name='clienteId']").val(result.result.clienteId);
                                enabledBtn();
                                showSuccessToastr(result.result.message);
                            }
                        } else { 
                            showErrorToastr(result.result.message);
                        }
                    });
                } else {
                    // evitar la DETRACCIÓN 
                    showErrorToastr('Usted debe emitir boletas/facturas/guías por montos menores a ' + '{{env('DETRACCION')}}');
                }
            } else {
                // no ha superado la validación
                showErrorToastr('Complete los campos obligatorios');
            }
        }

        function printElement() {
            var encargoId = $("[name='encargoId']").val();
            if (encargoId) {
                $.ajax({
                    url: "{{ url('/api/v1/venta/comprobante') }}/" + encargoId,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    $("#comprobantePago").attr("src", result.result.urlComprobantePago);
                });
                return true;
            }
        }

        $("[name='docEnvia']").on('keypress', function(e) {
            if (e.which == 13) {
                var docEnvia = $("[name='docEnvia']").val().trim();
                if (docEnvia.length === 8) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + docEnvia,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombreEnvia']").val(result.result.nombre);
                    });
                    
                }
                else if(docEnvia.length === 11) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + docEnvia,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombreEnvia']").val(result.result.nombre);
                    });
                }
                else {
                    // consultar
                    alert("ha ingresado " + docEnvia.length +" caracteres, complételo por favor.");
                }
            }
        });
        
        $("[name='docRecibe']").on('keypress',function(e) {
            if (e.which == 13) {
                var docRecibe = $("[name='docRecibe']").val().trim();
                if (docRecibe.length === 8) {
                    // consultar a RENIEC
                    $.ajax({
                        url: "{{ url('/api/v1/reniec') }}/" + docRecibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombreRecibe']").val(result.result.nombre);
                    });
                    
                }
                else if(docRecibe.length === 11) {
                    // consultar a SUNAT
                    $.ajax({
                        url: "{{ url('/api/v1/sunat') }}/" + docRecibe,
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        dataType: "json"
                    }).done(function(result) {
                        $("[name='nombreRecibe']").val(result.result.nombre);
                    });
                }
                else {
                    // consultar
                    alert("ha ingresado " + docRecibe.length +" caracteres, complételo por favor.");
                }
            }
        });
    </script>
@endsection
