@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <a href="{{ url('venta/nuevo') }}" class="btn btn-primary">Nuevo</a>
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
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
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($encargo))
                            @foreach ($encargo as $item)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ url('/venta/editar/' . $item->id) }}"><img
                                                src="{{ asset('/assets/media/edit.svg') }}" width="24" /></a>
                                    </th>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td align="left">{{ $item->oferta }}</td>
                                    <td align="left">{{ $item->detraccion_monto }}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                    <td>
                                        @if ($item->url_documento_baja)
                                            <a target="_blank" href="{{ url('/api/v1/download/baja/' . $item->id) }}"><img
                                                    src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                                    width="24"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_pdf)
                                            <a target="_blank" href="{{ url('/api/v1/download/pdf/' . $item->id) }}"><img
                                                    src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                                    width="24"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_xml)
                                            <a target="_blank" href="{{ url('/api/v1/download/xml/' . $item->id) }}"><img
                                                    src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                                    width="24"></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->url_documento_cdr)
                                            <a target="_blank" href="{{ url('/api/v1/download/cdr/' . $item->id) }}"><img
                                                    src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                                    width="24"></a>
                                        @endif
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
