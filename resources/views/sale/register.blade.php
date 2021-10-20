@extends('layout.layout')
@section('content')
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <div class="row gy-5 g-xl-12">
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Envía:</label>
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


                <div class="row gy-5 g-xl-12">
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Origen:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected> -- </option>
                                    <option value="1">AREQUIPA</option>
                                    <option value="2">LIMA</option>
                                    <option value="3">TACNA</option>
                                </select>
                            </div>
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Destino:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected> -- </option>
                                    <option value="1">AREQUIPA</option>
                                    <option value="2">LIMA</option>
                                    <option value="3">TACNA</option>
                                </select>
                            </div>
                            <div class="col-xxl-6">
                                <label for="exampleDataList" class="form-label">Agencia:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected> -- </option>
                                    <option value="1">Av Nicolás Arriola, La Victoria 15034</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row gy-5">
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Medio de pago:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected> -- </option>
                                    <option value="1">CRÉDITO</option>
                                    <option value="2">CONTADO</option>
                                </select>
                            </div>
                            <div class="col-xxl-4">
                                <label for="exampleDataList" class="form-label">Documento:</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected> -- </option>
                                    <option value="1">BOLETA ELECTRÓNICA</option>
                                    <option value="2">FACTURA ELECTRÓNICA</option>
                                    <option value="3">GUÍA DE REMISIÓN</option>
                                </select>
                            </div>
                            <div class="col-xxl-2">
                                <label for="exampleDataList" class="form-label">Serie:</label>
                                <input class="form-control" list="datalistOptions" id="exampleDataList" placeholder="">
                            </div>
                            <div class="col-xxl-3">
                                <label for="exampleDataList" class="form-label">Número:</label>
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
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <th scope="col"><a><img src="{{ asset('assets/media/icons/sis/plus-circle.svg') }}"
                                        width="24" /></a></th>
                            <th scope="col">Descripción</th>
                            <th scope="col" width="100">Cantidad</th>
                            <th scope="col" width="100">Precio</th>
                            <th scope="col" width="100">Total</th>
                            <th scope="col" width="100">Peso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row" width="100">
                                <a><img src="{{ asset('assets/media/icons/sis/x-circle.svg') }}" width="24" /></a>
                            </td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control"></td>
                            <td><input type="text" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
                <br />
                <div class="row">
                    <div class="col-3">
                        <b>Conductor:</b> Gerson Sánchez Aguilar<br />
                        <b>Partida:</b> 08:00 PM
                    </div>
                    <div class="col-3">
                        <b>Encomienda:</b> por despachar<br />
                        <b>SUNAT:</b> pendiente<br />

                    </div>
                    <div class="col-2">
                        <div class="d-flex align-items-center w-100px w-sm-200px flex-column mt-2">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bold text-gray-400">envíos y viajes en el año</span>
                                <span class="fw-bolder">2</span>
                            </div>
                            <div class="h-5px mx-3 w-100 bg-light mb-3">
                                <div class="bg-success rounded h-5px" role="progressbar" style="width: 50%;"
                                    aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-end align-top">
                        <button class="btn btn-primary">Confirmar operación</button>
                        <button class="btn btn-secondary"><img src="{{ asset('assets/media/icons/sis/printer.svg') }}"
                                width="24" /></button>
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app"><img
                                src="{{ asset('assets/media/icons/sis/search.svg') }}" width="24" /></a>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
