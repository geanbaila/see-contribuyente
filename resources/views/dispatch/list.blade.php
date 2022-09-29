@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <div class="row gy-5 g-xl-12">
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Recibe / Envía:</label>
                                <input class="form-control" id="doc_recibe_envia" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="separator border-gray-200 mb-6"></div>
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Nuevos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Entregados</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <table
                            class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                            <thead class="border-gray-200 fw-bold bg-lighten">
                                <tr>
                                    <th valign="top" scope="col" width="50"></th>
                                    <th valign="top" scope="col" width="100">F. entrega</th>
                                    <th valign="top" scope="col" width="100">Ítems</th>
                                    <th valign="top" scope="col" width="120">Documento</th>
                                    <th valign="top" scope="col" width="200">Recibe</th>
                                    <th valign="top" scope="col" width="200">Envía</th>
                                    <th valign="top" scope="col" width="100">F. recepción</th>
                                    <th valign="top" scope="col" width="110">Origen</th>
                                    <th valign="top" scope="col" width="110">Destino</th>
                                    <th valign="top" scope="col" width="110">Penalidad (S/.)</th>
                                </tr>
                            </thead>
                            <tbody id="responseDispatcherRow">
                                @if (!empty($encargo))
                                    @foreach ($encargo as $item)
                                        @if ($item->fecha_hora_recibe == '' || $item->fecha_hora_recibe == null)
                                            @php
                                                $background = $item->documento_id == 3 ? 'table-danger' : '';
                                            @endphp
                                            <tr class="{{ $background }}">
                                                <th scope="row">
                                                    @if ($item->documento_id != 3)
                                                        <a
                                                            onclick="javascript:entregarPaquete('{{ $item->id }}', this)">
                                                            <img src="{{ asset('public/assets/media/package.svg') }}"
                                                                width="20" /></a>
                                                    @else
                                                        <a
                                                            onclick="javascript:prepararComprobante('{{ $item->documento_serie }}-{{ $item->documento_correlativo }}')">
                                                            <img src="{{ asset('public/assets/media/credit-card.svg') }}"
                                                                width="20" /></a>
                                                    @endif
                                                </th>
                                                <td>{!! str_replace(' ', '<br>', $item->fecha_hora_recibe) !!}</td>
                                                <td>{{ $item->cantidad_item }}</td>
                                                <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                                <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                                <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                                <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                                <td>
                                                    {{ $item->agencia_origen_nombre }}
                                                </td>
                                                <td>
                                                    {{ $item->agencia_destino_nombre }}
                                                </td>
                                                <td align="center">
                                                    0.00
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="12">{{ $encargo->links() }}</td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="10" align="center"><img class="loading-modal" style="display:none"
                                            src="{{ asset('public/assets/media/loading.gif') }}" width="20" /></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <table
                            class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                            <thead class="border-gray-200 fw-bold bg-lighten">
                                <tr>
                                    <th valign="top" scope="col" width="50"></th>
                                    <th valign="top" scope="col" width="100">F. entrega</th>
                                    <th valign="top" scope="col" width="100">Ítems</th>
                                    <th valign="top" scope="col" width="120">Documento</th>
                                    <th valign="top" scope="col" width="200">Recibe</th>
                                    <th valign="top" scope="col" width="200">Envía</th>
                                    <th valign="top" scope="col" width="100">F. recepción</th>
                                    <th valign="top" scope="col" width="110">Origen</th>
                                    <th valign="top" scope="col" width="110">Destino</th>
                                    <th valign="top" scope="col" width="110">Penalidad (S/.)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($encargo))
                                    @foreach ($encargo as $item)
                                        @if ($item->documento_id != 3 && $item->fecha_hora_recibe)
                                            <tr>
                                                <th scope="row"><img
                                                        src="{{ asset('public/assets/media/check-circle.svg') }}"
                                                        width="20" /></th>
                                                <td>{!! str_replace(' ', '<br>', $item->fecha_hora_recibe) !!}</td>
                                                <td>{{ $item->cantidad_item }}</td>
                                                <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                                <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                                <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                                <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                                <td>
                                                    {{ $item->agencia_origen_nombre }}
                                                </td>
                                                <td>
                                                    {{ $item->agencia_destino_nombre }}
                                                </td>
                                                <td align="center">
                                                    0.00
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="12">{{ $encargo->links() }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var firstTabEl = document.querySelector('#myTab li:last-child a')
        var firstTab = new bootstrap.Tab(firstTabEl)
        firstTab.show()

        const RUC = 11;
        const DNI = 8;
        const CE = 12;

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
                $(".check-encargos").attr('checked', 'checked');
            } else {
                $(".check-encargos").attr('checked', false);
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

        function entregarPaquete(encargo_id, element) {
            $.ajax({
                url: "{{ url('/api/v1/despacho') }}/" + encargo_id,
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                beforeSend: function() {
                    $(element).html(
                        '<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="sr-only">por favor espere</span></div>'
                    );
                }
            }).done(function(response) {
                if (response.result.status === 'OK') {
                    var tr = $(element).parent().parent();
                    $(tr).find('td:eq(0)').html(response.result.fecha_hora_recibe);
                    $(element).parent().html(
                        '<img src="{{ asset('public/assets/media/check-circle.svg') }}" width="20" />');
                    showSuccessToastr(response.result.message);
                } else {
                    showErrorToastr(response.result.message);
                    $(element).html('<img src="{{ asset('public/assets/media/package.svg') }}" width="20" />');
                }
            }).fail(function() {
                $(element).html('<img src="{{ asset('public/assets/media/package.svg') }}" width="20" />');
                showErrorToastr('No se ha podido registrar la entrega del paquete.');
            });
        }

        function prepararComprobante(documento) {
            $("[name='nombre_recibe']").val('');
            $("[name='doc_recibe']").val('');
            $("[name='encargo_id']").val('');
            if (documento.length > 0) {
                $("#modalPrepararComprobante").modal('show');
                $.ajax({
                    url: "{{ url('/api/v1/guia-remision-transportista/any') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        doc_recibe_envia: '',
                        documento: documento
                    },
                    beforeSend: function() {
                        $("#responseChargeRow2").html("");
                        //$(".loading-modal").show();
                    }
                }).done(function(response) {
                    //$(".loading-modal").hide();
                    var html = '<tr><td colspan="6" align="center">No se encontraron coincidencias</td></tr>';
                    if (response.result.encargo.length > 0) {
                        _.forEach(response.result.encargo, function(element, index) {
                            $("[name='encargo_id']").val(element.id);
                            html = '<tr>' +
                                '<td>' + element.doc_envia + '<br>' + element.nombre_envia + '</td>' +
                                '<td>' + element.doc_recibe + '<br>' + element.nombre_recibe + '</td>' +
                                '<td>' + element.direccion + '</td>' +
                                '<td>' + element.documento_fecha + '<br>' + element.documento_hora +
                                '</td>' +
                                '<td>' + element.oferta + '</td>' +
                                '<td>0</td>' +
                                '</tr>';
                            $("#responseChargeRow2").append(html);
                        });
                    } else {
                        $("#responseChargeRow2").html(html);
                    }
                });
            } else {
                alert('no hay suficientes valores')
            }
        }

        function crearComprobante() {
            $("#btnPagarModal").prop('disabled', true);
            var data = {
                documento: $("[name='documento']").val(),
                doc_recibe: $("[name='doc_recibe']").val(),
                nombre_recibe: $("[name='nombre_recibe']").val(),
                encargo_id: $("[name='encargo_id']").val(),
            }
            var irregular = _.find(data, function(o) {
                return o.documento == '--' || o.doc_recibe == '' || o.nombre_recibe == '';
            })
            if (!irregular) {
                $.ajax({
                    url: "{{ url('/api/v1/guia-remision-transportista/conversion') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        $(".loading-input2").show();
                    }
                }).done(function(response) {
                    $(".loading-input2").hide();
                    if (response.result.status == 'OK') {
                        var pagado = $("#responseChargeRow2 td:nth-child(5)").text();
                        $("#responseChargeRow2 td:nth-child(5)").html("0");
                        $("#responseChargeRow2 td:nth-child(6)").html(pagado);
                        showSuccessToastr(response.result.message);
                    } else {
                        showErrorToastr(response.result.message);
                        $("#btnPagarModal").prop('disabled', false);
                    }

                    // limpiar modal
                    // cambiar ícono


                });
            }


        }

        $('#doc_recibe_envia').focus();

        $("#doc_recibe_envia").on('keypress', function(e) {
            if (e.which == 13) {
                var doc_recibe_envia = $(this).val();
                $.ajax({
                    url: "{{ url('/api/v1/despacho/any') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    data: {
                        doc_recibe_envia: doc_recibe_envia
                    },
                    beforeSend: function() {
                        $("#responseDispatcherRow").html("");
                        $(".loading-modal").show();
                    }
                }).done(function(response) {
                    $(".loading-modal").hide();
                    var html =
                        '<tr><td colspan="10" align="center">No se encontraron coincidencias</td></tr>';
                    if (response.result.encargo.data.length > 0) {
                        _.forEach(response.result.encargo.data, function(element, index) {
                            var click_event = ''
                            if (element.documento_id != 3) {
                                if (element.fecha_hora_recibe != null && element.fecha_hora_recibe
                                    .length > 0) {
                                    click_event =
                                        '<img src="{{ asset('public/assets/media/check-circle.svg') }}" width="20" />';
                                } else {
                                    click_event = '<a onclick="javascript:entregarPaquete(' +
                                        element.id +
                                        ', this)"><img src="{{ asset('public/assets/media/package.svg') }}" width="20" /></a>';
                                }
                            }
                            if (element.documento_id == 3) {
                                click_event = '<a onclick="javascript:prepararComprobante(\'' +
                                    element
                                    .documento_serie + '-' + element.documento_correlativo +
                                    '\')"><img src="{{ asset('public/assets/media/credit-card.svg') }}" width="20" /></a>';
                            }
                            html = '<tr>' +
                                '<th scope="row">' + click_event + '</th>' +
                                '<td>' + (element.fecha_hora_recibe ? element.fecha_hora_recibe :
                                    '') + '</td>' +
                                '<td>' + element.cantidad_item + '</td>' +
                                '<td>' + element.documento_serie + '-' + element
                                .documento_correlativo + '</td>' +
                                '<td>' + element.doc_recibe + '<br>' + element.nombre_recibe +
                                '</td>' +
                                '<td>' + element.doc_envia + '<br>' + element.nombre_envia +
                                '</td>' +
                                '<td>' + element.fecha_hora_envia + '</td>' +
                                '<td>' + element.agencia_origen_nombre + '</td>' +
                                '<td>' + element.agencia_destino_nombre + '</td>' +
                                '<td align="center">0.00</td>' +
                                '</tr>';
                            $("#responseDispatcherRow").append(html);
                        });
                    } else {
                        $("#responseDispatcherRow").html(html);
                    }
                });

            }
        });

        $("[name='doc_recibe']").on('keydown', function(e) {
            if (e.which == 8) {
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
                        beforeSend: function() {
                            $(".loading-input2").show();
                        }
                    }).done(function(result) {
                        $(".loading-input2").hide();
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
                        beforeSend: function() {
                            $(".loading-input2").show();
                        }
                    }).done(function(result) {
                        $(".loading-input2").hide();
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

        $("#modalPrepararComprobante").on("hidden.bs.modal", function() {
            window.location.reload();
        });
    </script>
@endsection
