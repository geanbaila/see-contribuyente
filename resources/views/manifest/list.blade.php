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

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <p class="fs-6 fw-bold">Cusco</p>
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th scope="col" width="80"></th>
                            <th scope="col" width="80">Vehículo</th>
                            <th scope="col" width="200">Conductor</th>
                            <th scope="col" width="80">Viajes</th>
                            <th scope="col" width="80">Partida</th>
                            <th scope="col" width="80">Llegada</th>
                            <th scope="col" width="100">Estado</th>
                            <th scope="col" width="80">Encomiendas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <p class="fs-6 fw-bold">Arequipa</p>
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th scope="col" width="80"></th>
                            <th scope="col" width="80">Vehículo</th>
                            <th scope="col" width="200">Conductor</th>
                            <th scope="col" width="80">Viajes</th>
                            <th scope="col" width="80">Partida</th>
                            <th scope="col" width="80">Llegada</th>
                            <th scope="col" width="100">Estado</th>
                            <th scope="col" width="80">Encomiendas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <p class="fs-6 fw-bold">Lima</p>
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <td scope="row" width="80"></td>
                            <th scope="col" width="80">Vehículo</th>
                            <th scope="col" width="200">Conductor</th>
                            <th scope="col" width="80">Viajes</th>
                            <th scope="col" width="80">Partida</th>
                            <th scope="col" width="80">Llegada</th>
                            <th scope="col" width="100">Estado</th>
                            <th scope="col" width="80">Encomiendas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/icons/sis/eye.svg') }}"></a>
                            </td>
                            <td>QWE-XCD</td>
                            <td>José Carlos Guzmán Sánchez</td>
                            <td>4666</td>
                            <td>29.09.2021<br />10:00PM</td>
                            <td>30.09.2021<br />5:00AM</td>
                            <td>Viajando</td>
                            <td>4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Container-->
@endsection
