@extends('master::layouts/admin')

@section('content')
  @if($parent)
    <h3>Seleccionar Tipo de Indicador</h3>
    <ul class="row">
      @foreach($items as $item_key => $item)
        @if($item->location=='app')
          <li class="col-sm-3"><a href="{{ $url.$item->id.'&type=normal&data=count' }}">{{ $item->singular }}</a></li>
        @endif
      @endforeach
    </ul>
  @endif
@endsection