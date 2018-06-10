@extends('master::layouts/admin')

@section('content')
  <h3>Formularios Dinámicos</h3>
  <h4><a href="{{ url('admin/form/create') }}">Crear Formulario Dinámico</a></h4>
  @if(count($items)>0)
    <table class="admin-table table table-striped table-bordered dt-responsive">
      <thead>
        <tr class="title">
          <td>#</td>
          <td>Nombre</td>
          <td>Permisos</td>
          <td>Campos</td>
          <td>PDF</td>
          <td>Editar</td>
          <td>Eliminar</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $key => $item)
          <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->plural }}</td>
            <td>{{ trans('master::admin.'.$item->permission) }}</td>
            <td>{{ $item->fields()->displayItem(['show','admin'])->count() }} | <a href="{{ url('admin/form-fields/'.$item->id) }}">Editar Campos</a></td>
            <td><a target="_blank" href="{{ url('admin/export-form/'.$item->id) }}">PDF</a></td>
            <td class="edit"><a href="{{ url('admin/form/edit/'.$item->id) }}">Editar</a></td>
            @if($item->trashed())
              <td class="restore"><a href="{{ url('admin/model/node/restore/'.$item->id) }}">Restaurar</a></td>
            @else
              <td class="delete"><a href="{{ url('admin/model/node/delete/'.$item->id) }}" onclick="return confirm('¿Está seguro que desea eliminar este item?');">Eliminar</a></td>
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>{{ trans('master::admin.no_items') }}</p>
  @endif
@endsection