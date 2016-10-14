@extends('master::layouts/admin')

@section('content')
  @if($parent)
    <h3>Seleccionar Tipo de Indicador</h3>
    <ul class="row">
      @foreach($items as $item_key => $item_name)
        @if($item_key!='0')
          <li class="col-sm-3"><a href="{{ $url.$item_key.'&type=normal&data=count' }}">{{ $item_name }}</a></li>
        @endif
      @endforeach
    </ul>
  @endif
@endsection