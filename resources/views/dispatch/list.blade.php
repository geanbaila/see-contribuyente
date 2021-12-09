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
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                            <div class="col-xxl-9">
                                <label for="exampleDataList" class="form-label">&nbsp;</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder=""
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">

                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">F. Recepción:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder=""
                                    disabled>
                            </div>
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
                    <tbody>
                        @if (!empty($encargo))
                            @foreach ($encargo as $item)
                                <tr>
                                    <th scope="row">
                                        <a onclick="javascript:entregarPaquete('{{ $item->id }}', this)"><img
                                                src="{{ asset('assets/media/package.svg') }}" width="20" /></a>
                                    </th>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_recibe) !!}</td>
                                    <td>{{ $item->cantidad_item }}</td>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td>
                                        {{ $item->agenciasOrigen->nombre }}
                                    </td>
                                    <td>
                                        {{ $item->agenciasDestino->nombre }}
                                    </td>
                                    <td align="center">
                                        0.00
                                    </td>
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
                $(element).html('<img src="{{ asset('assets/media/package.svg') }}" width="20" />');
            }).fail(function() {
                $(element).html('<img src="{{ asset('assets/media/package.svg') }}" width="20" />');
                showErrorToastr('No se ha podido registrar la entrega del paquete.');
            });
        }
    </script>
@endsection
