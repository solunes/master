@extends('master::layouts/admin')

@section('content')
  @if($parent)
    <h3>Seleccionar Campo Predefinido</h3>
    <h4>Elija una opción para "{{ trans('master::fields.'.$parent) }}":</h4>
    @if($single_model=='indicator')
    <?php $indicator_key = 19; ?>
    <h5><a href="{{ $url.$indicator_key.'&type=custom&custom=estudiante' }}">Crear Indicador Dinámico para Estudiantes</a></h5>
    <h5><a href="{{ $url.$indicator_key.'&type=custom&custom=docente' }}">Crear Indicador Dinámico para Docente</a></h5>
    <h5><a href="{{ $url.$indicator_key.'&type=custom&custom=empresa' }}">Crear Indicador Dinámico para Empresa</a></h5>
    @endif
    <ul class="row">
      @foreach($items as $item_key => $item_name)
        @if($item_key!='0')
          <li class="col-sm-3"><a href="{{ $url.$item_key }}">{{ $item_name }}</a></li>
        @endif
      @endforeach
    </ul>
  @endif
@endsection