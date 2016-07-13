@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      {!! Segment::form_parent($i, $action, $id, 'customer_id') !!}
      {!! Segment::form_input($i, 'city_id', 'select', ['options'=>$city_options]) !!}
      {!! Segment::form_input($i, 'status', 'select', ['options'=>$status_options]) !!}
      {!! Segment::form_input($i, 'name', 'text') !!}
      {!! Segment::form_input($i, 'type', 'select', ['options'=>$type_options]) !!}
      {!! Segment::form_input($i, 'address','text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'assigned_staff', 'text') !!}
      {!! Segment::form_input($i, 'contract_signed', 'text', ['class'=>'datepicker']) !!}
      {!! Segment::form_input($i, 'contract_duration', 'text') !!}
      {!! Segment::form_input($i, 'phone','text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'observations', 'textarea', ['required'=>false]) !!}
    </div>
    <div class="child">
      <h3>PISOS</h3>
      <div class="table-responsive">
        <table class="table" id="floors">
          <thead><tr class="title">
            <td>Nombre del Piso (*)</td>
            <td>Código QR</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->floors)>0)
              @foreach($i->floors as $key => $si)
                @include('item_child.model', ['count'=>$key])
              @endforeach
            @else
              @include('item_child.model', ['count'=>0, 'si'=>0])
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="floors" href="#" data-count="500">Añadir nuevo piso</a>
    </div>
    <div class="child">
      <h3>HORARIOS</h3>
      <div class="table-responsive">
        <table class="table" id="schedules">
          <thead><tr class="title">
            <td>Estado (*)</td>
            <td>Día (*)</td>
            <td>Hora de Inicio (*)</td>
            <td>Hora de Salida (*)</td>
            <td>Observaciones</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->schedules)>0)
              @foreach($i->schedules as $key => $si)
                @include('item_child.point-schedule', ['count'=>$key])
              @endforeach
            @else
              @include('item_child.point-schedule', ['count'=>0, 'si'=>0])
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="schedules" href="#" data-count="500">Añadir nuevo horario</a>
    </div>
    <div class="child">
      <h3>REQUERIMIENTO DE INSUMOS MENSUAL</h3>
      <div class="table-responsive">
        <table class="table" id="products">
          <thead><tr class="title">
            <td>Insumo (*)</td>
            <td>Cantidad Requerida (*)</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->products)>0)
              @foreach($i->products as $key => $si)
                @include('item_child.point-product', ['name'=>'products', 'product_id'=>NULL, 'count'=>$key])
              @endforeach
            @else
              @include('item_child.point-product', ['name'=>'products', 'product_id'=>NULL, 'count'=>0, 'si'=>0])
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="products" href="#" data-count="500">Añadir nuevo producto</a>
    </div>
    {!! Segment::form_submit($i, $action) !!}

  {!! Form::close() !!}
@endsection
@section('script')
  @include('scripts.child-js')
@endsection