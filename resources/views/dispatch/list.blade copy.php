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
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">F. Recepción:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="" disabled>
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
                            <th valign="top" scope="col" width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" onclick="javacript:seleccionarEncargos(this)"/>
                                </div>
                            </th>
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
                                            <input class="form-check-input check-encargos" type="checkbox" value="" />
                                        </div>
                                    </th>
                                    <td>{{ $item->documento_serie }}-{{ $item->documento_correlativo }}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_envia) !!}</td>
                                    <td>{!! str_replace(' ', '<br>', $item->fecha_hora_recibe) !!}</td>
                                    <td>{{ $item->doc_envia }}<br>{{ $item->nombre_envia }}</td>
                                    <td>{{ $item->doc_recibe }}<br>{{ $item->nombre_recibe }}</td>
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
    function seleccionarEncargos(parent) {
        if($(parent).is(':checked')){
            $(".check-encargos").attr('checked', 'checked');
        } else {
            $(".check-encargos").attr('checked', false);
        }
    }
</script>
@endsection
