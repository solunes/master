@extends('master::layouts/admin-2')

@section('content')
  @if($parent)
    <h3>Seleccionar Campo Predefinido</h3>
    <h4>Elija una opción para "{{ trans('master::fields.'.$parent) }}":</h4>
    <ul class="row">
      @foreach($items as $item_key => $item_name)
        @if($item_key!='0')
          <li class="col-sm-3"><a href="{{ $url.$item_key }}">{{ $item_name }}</a></li>
        @endif
      @endforeach
    </ul>
  @endif
@endsection