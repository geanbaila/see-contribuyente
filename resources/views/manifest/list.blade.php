@extends('layout.layout')
@section('content')
    <div class="separator border-gray-200 mb-6"></div>
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-header border-0 pt-5">
                <div class="col-xxl-3 col-sm-3">
                    <label for="exampleDataList" class="form-label">Origen:</label>
                    <select class="form-select" aria-label="--" name="agencia_origen"
                        onchange="javascript:getAgenciaDestino(this.value, false);">
                        <option value="--" selected> -- </option>
                        @if (isset($origen))
                            @foreach ($origen as $item)
                                <option value="{{ $item->id }}" data-sede="{{ $item->sede }}"> {{ $item->nombre }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-xxl-3 col-sm-3">
                    <label for="exampleDataList" class="form-label">Destino:</label>
                    <select class="form-select" aria-label="--" name="agencia_destino" onchange="javascript:getData()">
                        <option value="--" selected> -- </option>
                    </select>
                </div>
                <div class="col-xxl-2  col-sm-2">
                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                    <button class="form-control btn btn-primary" onclick="javascript:transportar()">Transportar</button>
                </div>
                <div class="col-xxl-2  col-sm-2">
                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                    <button class="form-control btn btn-primary" onclick="javascript:noTransportar()">No
                        transportar</button>
                </div>
                <div class="col-xxl-1  col-sm-1">
                    <label for="exampleDataList" class="form-label">&nbsp;</label>
                    <a id="btnImprimir" class="form-control btn btn-secondary disabled" data-bs-toggle="modal"
                        data-bs-target="#modalImprimirComprobante" onclick="javascript:printElement()">
                        <img src="{{ asset('assets/media/printer.svg') }}" width="24" />
                    </a>
                </div>

            </div>

            <div class="card-body pt-9 pb-0">
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th valign="top" scope="col" width="50">
                                <input class="form-check-input" type="checkbox" value=""
                                    onclick="javascript:seleccionarEncargos(this)">
                            </th>
                            <th valign="top" scope="col" width="110">Estado</th>
                            <th valign="top" scope="col" width="100">F. recepción</th>
                            <th valign="top" scope="col" width="100">Ítems</th>
                            <th valign="top" scope="col" width="120">Documento</th>
                            <th valign="top" scope="col" width="200">Recibe</th>
                            <th valign="top" scope="col" width="200">Envía</th>
                            <th valign="top" scope="col" width="110">Origen</th>
                            <th valign="top" scope="col" width="110">Destino</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($encargo))
                            @foreach ($encargo as $item)
                                <tr>
                                    <th scope="row">
                                        <input class="form-check-input check-encargos" type="checkbox"
                                            value="{{ $item->id }}">
                                    </th>
                                    <td>{{ $item->estado }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td>{{ $item->cantidad_item }}</td>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                    <td>{{ $item->agenciasOrigen->nombre }}</td>
                                    <td>{{ $item->agenciasDestino->nombre }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
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

        function seleccionarEncargos(parent) {
            if ($(parent).is(':checked')) {
                $(".check-encargos").attr('checked', true);
            } else {
                $(".check-encargos").attr('checked', false);
            }
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
                    console.log(result);
                    if (result) {
                        _.forEach(result, function(element, index) {
                            agencia_destino_selected = (selected == element._id) ? "selected" : "";
                            $("[name='agencia_destino']").append("<option value='" + element._id + "' " +
                                agencia_destino_selected + ">" +
                                element.direccion + "</option>");

                        });
                    }
                });
            } else {
                // no hay agencia_destino, me quedo en vacío
            }
        }

        function transportar() {
            var encargos = [];
            $(".check-encargos").each(function(key, item) {
                if ($(item).is(':checked')) {
                    encargos.push($(item).val());
                }
            }).promise().done(function() {
                $.ajax({
                    url: "{{ url('/api/v1/manifiesto/transportar') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // contentType: false,
                    // processData: false,
                    dataType: "json",
                    data: {
                        encargos: encargos
                    }
                }).done(function(response) {
                    if (response) {
                        if(response.result.status === 'OK') {
                            showSuccessToastr(response.result.message);
                        }else {
                            showErrorToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr('No se ha podido registrar el estado del paquete.');
                    }
                });
            });
        }

        function noTransportar() {
            var encargos = [];
            $(".check-encargos").each(function(key, item) {
                if (!$(item).is(':checked')) {
                    encargos.push($(item).val());
                }
            }).promise().done(function() {
                $.ajax({
                    url: "{{ url('/api/v1/manifiesto/no-transportar') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    // contentType: false,
                    // processData: false,
                    dataType: "json",
                    data: {
                        encargos: encargos
                    }
                }).done(function(response) {
                    if (response) {
                        if(response.result.status === 'OK') {
                            showSuccessToastr(response.result.message);
                        }else {
                            showErrorToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr('No se ha podido registrar el estado del paquete.');
                    }
                });
            });
        }
    </script>
@endsection
