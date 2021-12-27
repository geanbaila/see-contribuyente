@extends('layout.layout')
@section('content')    
    <div class="card">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <table
                    class="table table-responsive table-striped table-flush align-middle table-row-bordered table-row-solid gy-4">
                    <thead class="border-gray-200 fw-bold bg-lighten">
                        <tr>
                            <td scope="row" width="10"></td>
                            <th scope="col" width="180">Reporte</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr>
                            <td valign="top" scope="row" class="text-center">
                                <a><img src="{{ asset('assets/media/eye.svg') }}" width="20"></a>
                            </td>
                            <td><p>Informe económico</p>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>

    <!--begin::Separator-->
    <div class="separator border-gray-200 mb-6"></div>
    <!--end::Separator-->

    <!--end::Container-->
@endsection
 