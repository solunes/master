@extends('master::layouts/admin')

@section('content')
  <h1>Elegir nodos para importar</h1>
  @if(count($items)>0)
    {!! Form::open(['url'=>'admin/import-nodes', 'method'=>'post', 'files'=>true]) !!}
      <table class="admin-table table table-striped table-bordered table-hover dt-responsive">
        <thead>
          <tr class="title">
            <td>Nº</td>
            <td>Nombre del nodo</td>
            <td>Nombre a utilizar en hoja de Excel</td>
            <td>Descargar muestra</td>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $key => $item)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $item->plural }}</td>
              <td>{{ $item->name }}</td>
              <td><a href="{{ url('admin/export-node/'.$item->name) }}">Descargar muestra</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <h4>Seleccione su archivo en XLS o XLSX</h4>
      <p>Recuerde que debe tener el mismo formato bajado en el descargador masivo, caso contrario pueden haber errores.</p>
      {!! Form::file('file') !!}
      <input class="btn btn-site" type="submit" value="Subir" />
    {!! Form::close() !!}
  @else
    <p>{{ trans('master::admin.no_items') }}</p>
  @endif
@endsection