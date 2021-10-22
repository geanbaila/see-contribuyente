@extends('layout.layout')
@section('content')
@if($encargo)
@foreach($encargo as $item)
{{$item->_id}}
@endforeach
@endif
@endsection