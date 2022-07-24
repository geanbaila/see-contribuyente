@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <a href="{{ url('venta/nuevo') }}" class="btn btn-primary">Nuevo</a>
                <a id="btnEliminar" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEliminarVenta">
                    <img src="https://www.corpenlaces.com/panel/public/assets/media/trash-2-white.svg" width="20">
                </a>
                <br/>
                <br/>
                <table
                    class="table table-hover table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Documento</th>
                            <th scope="col">F. recepción</th>
                            <th scope="col" width="150">Importe (S/.)</th>
                            <th scope="col" width="150">Detracción (S/.)</th>
                            <th scope="col">Envía</th>
                            <th scope="col">Recibe</th>
                            <th scope="col">Baja</th>
                            <th scope="col">PDF</th>
                            <th scope="col">XML</th>
                            <th scope="col">CDR</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($encargo))
                            @foreach ($encargo as $item)
                                <tr>
                                    <td>
                                        <a href="{{ url('/venta/editar/' . $item->id) }}"><img
                                                src="{{ asset('public/assets/media/edit.svg') }}" width="20" /></a>
                                    </td>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td align="left">{{ $item->oferta }}</td>
                                    <td align="left">{{ $item->detraccion_monto }}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                    <td>
                                        @if ($item->url_documento_baja)
                                            <a target="_blank"
                                                href="{{ url('/api/v1/download/baja/' . $item->id) }}"><img
                                                    src="https://www.corpenlaces.com/panel/public/assets/media/file-text.svg"
                                                    width="20"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_pdf)
                                            <a target="_blank" href="{{ url('/api/v1/download/pdf/' . $item->id) }}"><img
                                                    src="https://www.corpenlaces.com/panel/public/assets/media/file-text.svg"
                                                    width="20"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_xml)
                                            <a target="_blank" href="{{ url('/api/v1/download/xml/' . $item->id) }}"><img
                                                    src="https://www.corpenlaces.com/panel/public/assets/media/file-text.svg"
                                                    width="20"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_cdr)
                                            <a target="_blank" href="{{ url('/api/v1/download/cdr/' . $item->id) }}"><img
                                                    src="https://www.corpenlaces.com/panel/public/assets/media/file-text.svg"
                                                    width="20"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$item->url_documento_baja)
                                            <input class="form-check-input check-encargos" type="checkbox"
                                                data-verificado="{{ !empty($item->url_documento_cdr) ? '1' : '0' }}"
                                                value="{{ $item->id }}" />
                                        @endif
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

        function bajaCPE() {
            var encargos = [];
            $(".check-encargos").each(function(key, item) {
                if ($(item).is(':checked')) {
                    if ($(item).data('verificado') == '1') {
                        encargos.push($(item).val());
                        // $(item).parent().parent().remove();
                    } else {
                        $(item).attr('checked', false);
                    }
                }
            }).promise().done(function() {
                if (encargos.length > 0) {
                    $.ajax({
                        url: "{{ url('/venta/comunicar-baja') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: "json",
                        data: {
                            encargo_id: encargos
                        },
                        beforeSend: function() {
                            $("#btnBajaCPE").html(
                                '<div class="spinner-border spinner-border-sm text-light" role="status"><span class = "sr-only" > por favor espere < /span></div > Enviando '
                                );
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
                        showErrorToastr(
                        'No se pudo comunicar la baja del comprobante de pago electrónico.');
                    });
                } else {
                    showErrorToastr('Debe seleccionar al menos un comprobante de pago electrónico.');
                }
            });
        }
    </script>
@endsection
