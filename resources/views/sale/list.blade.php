@extends('layout.layout')
@section('content')
<a href="{{url('venta/nuevo')}}" class="btn btn-primary">Nuevo</a>
    @if ($encargo)
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Envía</th>
                    <th scope="col">Recibe</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($encargo as $item)
                    <tr>
                        <th scope="row">
                            <a href="{{url('/venta/editar/'. $item->_id)}}"><img src="{{asset('/assets/media/icons/sis/edit.svg')}}" width="24" /></a>
                        </th>
                        <td>{{ $item->nombre_envia }}</td>
                        <td>{{ $item->nombre_recibe }}</td>
                        <td>{{ $item->_id }}</td>
                    </tr>
                @endforeach
            </tbody>
    @endif
    </table>
@endsection
