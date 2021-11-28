@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th valign="top" scope="col" width="50">#</th>
                            <th valign="top" scope="col" width="120">Documento</th>
                            <th valign="top" scope="col" width="100">F. recepción</th>
                            <th valign="top" scope="col" width="100">F. entrega</th>
                            <th valign="top" scope="col">Envía</th>
                            <th valign="top" scope="col">Recibe</th>
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
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                              
                                            </label>
                                          </div>
                                    </th>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_recibe) !!}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
                                    <td>
                                        {{ $item->agenciasOrigen->direccion }}
                                    </td>
                                    <td>
                                        {{ $item->agenciasDestino->direccion }}
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
