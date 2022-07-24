@extends('layout.layout')
@section('content')
    <!--begin::Container-->
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">


                <div class="row gy-5 g-xl-12">
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Recibe:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                            <div class="col-xxl-9">
                                <label for="exampleDataList" class="form-label">&nbsp;</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Celular:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                            <div class="col-xxl-6">
                                <label for="exampleDataList" class="form-label">E-mail:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Fecha:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
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
                @foreach ($sede as $s)
                <br />
                <p class="fs-6 fw-bold">{{$s->nombre}}</p>
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <td scope="row" width="50"></td>
                            <th scope="col" width="180">Agencia</th>
                            <th scope="col" width="80">Vehículo</th>
                            <th scope="col" width="100">Conductor</th>
                            <th scope="col" width="70">Partida</th>
                            <th scope="col" width="70">Llegada</th>
                            <th scope="col" width="100">Estado</th>
                            <th scope="col" width="80">Encomiendas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salida as $item)
                        @if ($item->agencias->sedes->id == $s->id)
                        <?php
                        $status = ($item->horario < $ahora) ? 'viajando' : 'pronto partirá';
                        $t = explode(':',$item->horario);
                        $t[0] = $t[0]+16;
                        $t[0] = ($t[0]>24) ? $t[0]-24 : $t[0];
                        
                        ?>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('public/assets/media/eye.svg') }}" width="20"></a>
                            </td>
                            <td>{{$item->agencias->nombre}}</td>
                            <td>{{$item->$columna}}</td>
                            <td>Gean Carlos Baila Laurente</td>
                            <td>{{$item->horario}}</td>
                            <td>{{str_pad($t[0], 2, '0', STR_PAD_LEFT).':'.$t[1]}}</td>
                            <td>{{$status}}</td>
                            <td>0</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </div>

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <!--end::Container-->
@endsection
