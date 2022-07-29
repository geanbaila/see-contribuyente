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
                            <!--
                            <div class="col-xxl-9">
                                <label for="exampleDataList" class="form-label">&nbsp;</label>
                                <input class="form-control" disabled>
                            </div>
                            -->
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <!--
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">F. Recepción:</label>
                                <input class="form-control" disabled>
                            </div>
                            -->
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
                                <tr>
                                    <th scope="row">
                                        @if ($item->documento_id != 3)
                                            <a onclick="javascript:entregarPaquete('{{ $item->id }}', this)"><img
                                                src="{{ asset('public/assets/media/package.svg') }}" width="20" /></a>
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
                            @endforeach
                            <tr><td colspan="12">{{ $encargo->links() }}</td></tr>
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
                $(".check-encargos").attr('checked', 'checked');
            } else {
                $(".check-encargos").attr('checked', false);
            }
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
                    showSuccessToastr(response.result.message);
                } else {
                    showErrorToastr(response.result.message);
                }
                $(element).html('<img src="{{ asset('public/assets/media/package.svg') }}" width="20" />');
            }).fail(function() {
                $(element).html('<img src="{{ asset('public/assets/media/package.svg') }}" width="20" />');
                showErrorToastr('No se ha podido registrar la entrega del paquete.');
            });
        }

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
                    beforeSend: function () {
                        $("#responseDispatcherRow").html("");
                        // $("#loading-modal").show();
                    }
                }).done(function(response) {
                    // $("#loading-modal").hide();
                    var html = '<tr><td colspan="10" align="center">No se encontraron coincidencias</td></tr>';
                    console.log(response.result.encargo.data.length)
                    if (response.result.encargo.data.length > 0) {
                        _.forEach(response.result.encargo.data, function(element, index) {
                            var click_event = (element.documento_id !=3)?'<a onclick="javascript:entregarPaquete('+element.id +', this)"><img src="{{ asset('public/assets/media/package.svg') }}" width="20" /></a>':'';
                            html = '<tr>' +
                                '<th scope="row">' + click_event + '</th>' +
                                '<td>' + element.fecha_hora_recibe + '</td>' +
                                '<td>' + element.cantidad_item + '</td>'+
                                '<td>' + element.documento_serie + '-' + element.documento_correlativo + '</td>'+
                                '<td>' + element.doc_recibe + '<br>' + element.nombre_recibe + '</td>'+
                                '<td>' + element.doc_envia +'<br>' + element.nombre_envia + '</td>'+
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
        $('#doc_recibe_envia').focus();
    </script>
@endsection
