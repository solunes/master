@extends('master::layouts/admin')

@section('content')
  <h1>Formularios del Usuario: {{ $user->name }}</h1>
  @if(count($items)>0)
    <table class="admin-table table table-striped table-bordered dt-responsive">
      <thead>
        <tr class="title">
          <td>ID</td>
          <td>Formulario</td>
          <td>Estado</td>
          <td>Abrir Formulario</td>
        </tr>
      </thead>
      <tbody>
        <?php $form_array = \Solunes\Master\App\Node::where('additional_permission','admin')->lists('name')->toArray(); ?>
        @foreach ($items as $item)
          @if(Auth::user()->id==1||Auth::user()->hasRole('admin')||Auth::user()->hasRole('coordinador')||Auth::user()->hasRole('gestor')||!in_array($item->model, $form_array))
            <tr>
              <td>{{ $item->id }}</td>
              <td>{{ trans_choice('model.'.$item->model, 1) }}</td>
              <td>{{ trans('admin.'.$item->status) }}</td>
              <td>
                @if(Auth::user()->hasRole('admin')||Auth::user()->hasRole('coordinador')||Auth::user()->hasRole('gestor')||$item->status=='holding')
                  <a href="{{ url('admin/model/'.$item->model.'/edit/'.$item->model_id) }}">Abrir Formulario</a>
                @else
                  <a href="{{ url('admin/model/'.$item->model.'/view/'.$item->model_id) }}">Revisar Formulario</a>
                @endif
              </td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  @endif
@endsection