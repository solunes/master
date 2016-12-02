@extends('master::layouts/admin')

@section('content')
  <h1>Indicadores</h1>
  <h4><a href="{{ url('admin/model/indicator/create') }}">Crear indicador</a></h4>
  @if(count($indicators)>0)
    <table class="admin-table table table-bordered dt-responsive">
      <thead>
        <tr class="title">
          <td>ID</td>
          <td>Tipo</td>
          <td>Características</td>
          <td>Acción / Quitar de Dashboard</td>
        </tr>
      </thead>
      <tbody>
        @foreach($indicators as $key => $item)
          <tr class="title">
            <td>{{ ($key+1) }}</td>
            <td colspan="3">
              Indicador: {{ $item->name }} / Último Valor: {{ $item->value }} / 
              <a target="_blank" href="{{ url('admin/model/indicator/edit/'.$item->id) }}">(Editar Indicador)</a>
            </td>
          </tr>
          @foreach ($item->indicator_graphs as $subkey => $subitem)
            <tr>
              <td>{{ ($key+1).'.'.($subkey+1) }}</td>
              <td>GRÁFICO</td>
              <td>{{ trans('master::admin.'.$subitem->graph) }}
                @if($subitem->goal)
                 | META: {{ $subitem->goal }}
                @endif
              </td>
              @if(auth()->user()->hasIndicatorGraph($subitem->id))
              <td class="delete"><a href="{{ url('admin/change-indicator-user/graph/remove/'.$subitem->id) }}">Ocultar</a></td>
              @else
              <td class="edit"><a href="{{ url('admin/change-indicator-user/graph/add/'.$subitem->id) }}">Agregar</a></td>
              @endif
            </tr>
          @endforeach
          @foreach ($item->indicator_alerts as $subkey => $subitem)
            <tr>
              <td>{{ ($key+1).'.'.($subkey+1) }}</td>
              <td>ALERTA</td>
              <td>LÍMITE: {{ $subitem->final_date }} | META: {{ $subitem->goal }}}</td>
              @if(auth()->user()->hasIndicatorAlert($subitem->id))
              <td class="delete"><a href="{{ url('admin/change-indicator-user/alert/remove/'.$subitem->id) }}">Ocultar</a></td>
              @else
              <td class="edit"><a href="{{ url('admin/change-indicator-user/alert/add/'.$subitem->id) }}">Agregar</a></td>
              @endif
            </tr>
          @endforeach
        @endforeach
      </tbody>
    </table>
  @endif
@endsection