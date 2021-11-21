@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <a href="{{ url('venta/nuevo') }}" class="btn btn-primary">Nuevo</a>
                @if ($encargo)
                    <table class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                        <thead class="border-gray-200 fw-bold bg-lighten">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Documento</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Importe total</th>
                                <th scope="col">Envía</th>
                                <th scope="col">Recibe</th>
                                <th scope="col">PDF</th>
                                <th scope="col">XML</th>
                                <th scope="col">CDR</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($encargo as $item)
                                <tr>
                                    <th scope="row">
                                        <a href="{{ url('/venta/editar/' . $item->_id) }}"><img
                                                src="{{ asset('/assets/media/edit.svg') }}" width="24" /></a>
                                    </th>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{{ $item->fecha_envia }}</td>
                                    <td>{{ $item->subtotal }}</td>
                                    <td>{{ $item->doc_envia}}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{$item->doc_recibe}}<br>{{ $item->nombre_recibe }}</td>
                                    <td><a target="_blank" href="{{url('/'.$item->url_documento_pdf)}}"><img
                                            src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                            width="24"></a>
                                    </td>
                                    <td><a target="_blank" href="{{url('/'.$item->url_documento_xml)}}"><img
                                            src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                            width="24"></a>
                                    </td>
                                    <td><a target="_blank" href="{{url('/'.$item->url_documento_cdr)}}"><img
                                        src="http://localhost/dev.enlaces.sis/public/assets/media/file-text.svg"
                                        width="24"></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                @endif
                </table>
            </div>
        </div>
    </div>
@endsection
