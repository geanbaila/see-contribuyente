@extends('layout.layout')
@section('content')
    <form action="{{ url('/venta/registrar') }}" method="POST">
        <input type="hidden" name="encargoId" value="" />
        <div class="card">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-6">
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
                                    <input class="form-control" id="fechaEnvia" name="fechaEnvia" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-6">
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
                                    <input class="form-control" id="fechaRecibe" name="fechaRecibe" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row gy-5 g-xl-12">
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Origen:</label>
                                    <select class="form-select" aria-label="Default select example" id="origen"
                                        name="origen">
                                        <option selected> -- </option>
                                        @if (count($sede))
                                            @foreach ($sede as $item)
                                                <option value="{{ $item->_id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Destino:</label>
                                    <select class="form-select" aria-label="Default select example" id="destino"
                                        name="destino" onchange="javascript:getAgencia(this)">
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
                                    <select class="form-select" aria-label="Default select example" id="agencia"
                                        name="agencia" onchange="javascript:getSerie()">
                                        <option value="--" selected> -- </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div class="row gy-5">
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Medio de pago:</label>
                                    <select class="form-select" aria-label="Default select example" id="medioPago"
                                        name="medioPago">
                                        <option value="--" selected> -- </option>
                                        <option value="1">CRÉDITO</option>
                                        <option value="2">CONTADO</option>
                                    </select>
                                </div>
                                <div class="col-xxl-4">
                                    <label for="exampleDataList" class="form-label">Documento:</label>
                                    <select class="form-select" aria-label="Default select example" id="documento"
                                        name="documento" onchange="javascript:getSerie()">
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
                                    <input class="form-control" id="documentoSerieBlocked" value="000" disabled>
                                    <input type="hidden" id="documentoSerie" name="documentoSerie">
                                </div>
                                <div class="col-xxl-3">
                                    <label for="exampleDataList" class="form-label">Número:</label>
                                    <input class="form-control" id="documentoNumero" name="documentoNumero"
                                        placeholder="">
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
                                <th scope="col"><a><img src="{{ asset('assets/media/icons/sis/plus-circle.svg') }}"
                                            width="24" /></a></th>
                                <th scope="col">Descripción</th>
                                <th scope="col" width="100">Cantidad</th>
                                <th scope="col" width="100">Precio</th>
                                <th scope="col" width="100">Total</th>
                                <th scope="col" width="100">Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row" width="100">
                                    <a><img src="{{ asset('assets/media/icons/sis/x-circle.svg') }}" width="24" /></a>
                                </td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                                <td><input type="text" class="form-control"></td>
                            </tr>
                        </tbody>
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
                            <a class="btn btn-primary" onclick="javascript:doit();">Confirmar operación</a>
                            <button class="btn btn-secondary"><img src="{{ asset('assets/media/icons/sis/printer.svg') }}"
                                    width="24" /></button>
                            <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app"><img
                                    src="{{ asset('assets/media/icons/sis/search.svg') }}" width="24" /></a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        function str_pad(value, length) {
            return (value.toString().length < length) ?
                str_pad("0" + value, length) : value;
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

            var origen = $("[name='origen']").val().trim();
            var destino = $("[name='destino']").val().trim();
            var agencia = $("[name='agencia']").val().trim();
            var medioPago = $("[name='medioPago']").val().trim();
            var documento = $("[name='documento']").val().trim();
            var documentoSerie = $("[name='documentoSerie']").val().trim();
            var documentoNumero = $("[name='documentoNumero']").val().trim();

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
            data.append("origen", origen);
            data.append("destino", destino);
            data.append("agencia", agencia);
            data.append("medioPago", medioPago);
            data.append("documento", documento);
            data.append("documentoSerie", documentoSerie);
            data.append("documentoNumero", documentoNumero);
            return data;
        }

        function validate(data) {
            if (data.get('docEnvia').length !== 8 && data.get('docEnvia').length !== 10) {
                alert('Documento incorrecto de quien Envía');
                return false;
            }
            if (data.get('nombreEnvia').length === 0) {
                alert('No se dispone de los nombre de quien Envía');
                return false;
            }
            if (data.get('docRecibe').length !== 8 && data.get('docRecibe').length !== 10) {
                alert('Documento incorrecto de quien Recibe');
                return false;
            }
            if (data.get('nombreRecibe').length === 0) {
                alert('No se dispone de los nombre de quien Recibe');
                return false;
            }
            if (data.get('origen').length === 2 && data.get('origen') === '--') {
                alert('No se dispone del origen');
                return false;
            }
            if (data.get('destino').length === 2 && data.get('destino') === '--') {
                alert('No se dispone del destino');
                return false;
            }
            if (data.get('agencia').length === 2 && data.get('agencia') === '--') {
                alert('No se dispone del agencia');
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

        function getAgencia(element) {
            var agencia = "<option value='--'>--</option>";
            var sedeId = $(element).val();
            if (sedeId !== '--') {
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
                        result.forEach(function(element, index) {
                            agencia = agencia + "<option value='" + element._id + "'>" + element.nombre +
                                "</option>";
                        })
                    }
                    $("[name='agencia']").html(agencia);
                });
            } else {
                // no hay agencia
                $("[name='agencia']").html(agencia);
            }
        }

        function getSerie() {
            var agenciaId = $("#agencia").val();
            var documentoId = $("[name='documento']").val();
            if (agenciaId !== '--' && documentoId !== '--') {
                $.ajax({
                    url: "{{ url('/api/v1/serie') }}/" + agenciaId + "/" + documentoId,
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    contentType: false,
                    processData: false,
                    dataType: "json"
                }).done(function(result) {
                    console.log(result);
                    if (result) {
                        var documentoSerie = str_pad(result[0].correlativo, 3);
                        $("#documentoSerieBlocked").val(documentoSerie);
                        $("[name='documentoSerie']").val(documentoSerie);
                    }
                });
            } else {
                // no hay serie para iniciar
                $("[name='documentoSerie']").val('');
            }
        }

        function doit() {
            var data = getChargeForm();
            if (validate(data)) {
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
                    if (data.get('encargoId').length === 0) {
                        $("[name='encargoId']").val(result.result.encargoId);
                    }
                    console.log(result.result._id);
                });
            }
        }
    </script>
@endsection
