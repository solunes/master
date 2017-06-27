@extends('master::layouts/admin')

@section('content')
  <h1>Elegir nodos para exportar</h1>
  @if(count($items)>0)
    {!! Form::open(['url'=>'admin/export-nodes', 'method'=>'post']) !!}
      <table class="admin-table table table-striped table-bordered table-hover dt-responsive">
        <thead>
          <tr class="title">
            <td>Nº</td>
            <td>Nombre</td>
            <td>Etiqueta</td>
            <td>X</td>
          </tr>
        </thead>
        <tbody>
          @foreach ($items as $key => $item)
            <tr>
              <td>{{ $key+1 }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->plural }}</td>
              <td><input class="checkbox-item" type="checkbox" name="nodes[]" value="{{ $item->name }}" /></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <a class="select-all-checkboxes" data-status="hide" href="#">Seleccionar todos</a><br>
      <input class="btn btn-site" type="submit" value="Descargar" />
    {!! Form::close() !!}
  @else
    <p>{{ trans('master::admin.no_items') }}</p>
  @endif
@endsection
@section('script')
  <script type="text/javascript">
    $(".select-all-checkboxes").click(function(){  //"select all" change 
        var status = $(this).data('status');
        if(status=='hide'){
          $(this).data('status', 'show');
          $(this).html('Quitar todos');
          var action = true;
        } else {
          $(this).data('status', 'hide');
          $(this).html('Seleccionar todos');
          var action = false;
        }
        $('input.checkbox-item').prop('checked', action);
      return false;
    });
  </script>
@endsection