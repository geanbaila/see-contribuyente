@extends('layout.layout')
@section('content')
    <div class="separator border-gray-200 mb-6"></div>
    <div class="card">
        <div class="col-xxl-12">
            <!--begin::Tables Widget 5-->
            <div class="card card-xxl-stretch mb-5 mb-xl-12">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bolder fs-3 mb-1">&nbsp;</span>
                        <span class="text-muted mt-1 fw-bold fs-7">&nbsp;</span>
                    </h3>
                    <div class="card-toolbar">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bolder px-4 me-1 active"
                                    data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">Encargos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bolder px-4 me-1"
                                    data-bs-toggle="tab" href="#kt_table_widget_5_tab_2">Manifiestos</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="tab-content">
                        <!--begin::Tap pane-->
                        <div class="tab-pane fade active show" id="kt_table_widget_5_tab_1">
                            <!--begin::content 1-->
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
                                                        <option value="{{ $item->id }}" data-sede="{{ $item->sede }}">
                                                            {{ $item->nombre }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-xxl-3 col-sm-3">
                                            <label for="exampleDataList" class="form-label">Destino:</label>
                                            <select class="form-select" aria-label="--" name="agencia_destino"
                                                onchange="javascript:getData()">
                                                <option value="--" selected> -- </option>
                                            </select>
                                        </div>
                                        <div class="col-xxl-2  col-sm-2">
                                            <label for="exampleDataList" class="form-label">&nbsp;</label>
                                            <button class="form-control btn btn-primary"
                                                onclick="javascript:noTransportar()">No Trasladar</button>
                                        </div>
                                        <div class="col-xxl-2  col-sm-2">
                                            <label for="exampleDataList" class="form-label">&nbsp;</label>
                                            <button class="form-control btn btn-primary"
                                                onclick="javascript:transportar()">Trasladar</button>
                                        </div>
                                        <!--
                                            <div class="col-xxl-1  col-sm-1">
                                                <label for="exampleDataList" class="form-label">&nbsp;</label>
                                                <a id="btnImprimir" class="form-control btn btn-secondary disabled" data-bs-toggle="modal"
                                                    data-bs-target="#modalImprimirComprobante" onclick="javascript:printElement()">
                                                    <img src="{{ asset('assets/media/printer.svg') }}" width="20" />
                                                </a>
                                            </div>
                                            -->
                                        <div class="col-xxl-1  col-sm-1">
                                            <label for="exampleDataList" class="form-label">&nbsp;</label>
                                            <a id="btnImprimir" class="form-control btn btn-primary"
                                                onclick="javascript:empaquetarEnvio()">
                                                <img src="{{ asset('assets/media/truck-white.svg') }}" width="20" />
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
                                                                @if ($item->estado == '3')
                                                                    {{-- <img src="{{asset('assets/media/arrow-down-right.svg')}}" width="20" /> --}}
                                                                    <input class="form-check-input check-encargos"
                                                                        type="checkbox" value="{{ $item->id }}"
                                                                        data-verificado="{{ ($item->estado == '1' || $item->estado == '3')?'1':'0' }}">
                                                                @else
                                                                    <input class="form-check-input check-encargos"
                                                                        type="checkbox" value="{{ $item->id }}" 
                                                                        data-verificado="{{ ($item->estado == '1' || $item->estado == '3')?'1':'0' }}">
                                                                @endif
                                                            </th>
                                                            <td>{{ $item->estados->nombre }}</td>
                                                            <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                                            <td>{{ $item->cantidad_item }}</td>
                                                            <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}
                                                            </td>
                                                            <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}
                                                            </td>
                                                            <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}
                                                            </td>
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
                            <!--end::content 1-->
                        </div>
                        <!--end::Tap pane-->
                        <!--begin::Tap pane-->
                        <div class="tab-pane fade" id="kt_table_widget_5_tab_2">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::content2-->
                                <table id="tblManifiesto" class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                                            <thead class="border-gray-200 fw-bold bg-lighten">
                                                <tr>
                                                    <th valign="top" scope="col" width="50"></th>
                                                    <th valign="top" scope="col" width="110">Manifiesto</th>
                                                    <th valign="top" scope="col" width="80">Fecha</th>
                                                    <th valign="top" scope="col" width="80">Hora</th>
                                                    <th valign="top" scope="col" width="110">Origen</th>
                                                    <th valign="top" scope="col" width="110">Destino</th>
                                                    <th valign="top" scope="col" width="110">Ítems</th>
                                                    <th valign="top" scope="col" width="110">Por pagar</th>
                                                    <th valign="top" scope="col" width="110">Pagado</th>
                                                    <th valign="top" scope="col" width="110">Total General</th>
                                                    <th valign="top" scope="col" width="50">PDF</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($manifiesto))
                                                    @foreach ($manifiesto as $item)
                                                        <tr>
                                                            <th scope="row"></th>
                                                            <td></td>
                                                            <td>{{ $item->fecha }}</td>
                                                            <td>{{ $item->hora }}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{{ $item->cantidad_item }}</td>
                                                            <td>{{ number_format($item->subtotal_por_pagar, 2, '.', '') }}</td>
                                                            <td>{{ number_format($item->subtotal_pagado, 2, '.', '') }}</td>
                                                            <td>{{ number_format($item->total_general, 2, '.', '') }}</td>
                                                            <td>
                                                                @if ($item->url_documento_pdf)
                                                                    <a target="_blank"
                                                                        href="{{ url('/api/v1/download/manifiesto/' . $item->id) }}"><img
                                                                            src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                                                            width="20"></a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                <!--end::content2-->
                            </div>
                            <!--end::Table-->
                        </div>
                        <!--end::Tap pane-->
                    </div>
                </div>
                <!--end::Body-->
            </div>
            <!--end::Tables Widget 5-->
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
                    dataType: "json",
                    data: {
                        encargos: encargos
                    }
                }).done(function(response) {
                    if (response) {
                        if (response.result.status === 'OK') {
                            showSuccessToastr(response.result.message);

                        } else {
                            showErrorToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr('No se ha podido registrar el estado del encargo.');
                    }
                });
            });
        }

        function noTransportar() {
            var encargos = [];
            $(".check-encargos").each(function(key, item) {
                if ($(item).is(':checked')) {
                    encargos.push($(item).val());
                }
            }).promise().done(function() {
                $.ajax({
                    url: "{{ url('/api/v1/manifiesto/no-transportar') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        encargos: encargos
                    }
                }).done(function(response) {
                    if (response) {
                        if (response.result.status === 'OK') {
                            showSuccessToastr(response.result.message);
                        } else {
                            showErrorToastr(response.result.message);
                        }
                    } else {
                        showErrorToastr('No se ha podido registrar el estado del encargo.');
                    }
                });
            });
        }

        function empaquetarEnvio() {
            var encargos = [];
            $(".check-encargos").each(function(key, item) {
                if ($(item).is(':checked')) {
                    if($(item).data('verificado') == '1') {
                        encargos.push($(item).val());
                        $(item).parent().parent().remove();
                    } else {
                        $(item).attr('checked', false);
                    }
                }
            }).promise().done(function() {
                if(encargos.length>0){
                    $.ajax({
                        url: "{{ url('/api/v1/manifiesto/empaquetar-envio') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        data: {
                            encargos: encargos
                        }
                    }).done(function(response) {
                        if (response) {
                            if (response.result.status === 'OK') {
                                showSuccessToastr(response.result.message);
                                var html = '<tr>';
                                    html += '<td>&nbsp;</td>';
                                    html += '<td></td>';
                                    html += '<td>'+response.result.manifiesto.fecha+'</td>';
                                    html += '<td>'+response.result.manifiesto.hora+'</td>';
                                    html += '<td></td>';
                                    html += '<td></td>';
                                    html += '<td>'+response.result.manifiesto.cantidad_item+'</td>';
                                    html += '<td>'+response.result.manifiesto.subtotal_por_pagar.toFixed(2)+'</td>';
                                    html += '<td>'+response.result.manifiesto.subtotal_pagado.toFixed(2)+'</td>';
                                    html += '<td>'+response.result.manifiesto.total_general.toFixed(2)+'</td>';
                                    html += '<td><a target="_blank" href="{{url("/")}}/'+response.result.manifiesto.url_documento_pdf+'"><img src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg" width="20"></a></td>';
                                    html += '</tr>';
                                $('#tblManifiesto').append(html);
                            } else {
                                showErrorToastr(response.result.message);
                            }
                        } else {
                            showErrorToastr('No se ha podido generar el manifiesto.');
                        }
                    });
                } else {
                    showErrorToastr('No se ha podido genear el manifiesto.<br>Los CPE deben estar listos para el traslado.');
                }
            });
        }
    </script>
@endsection
