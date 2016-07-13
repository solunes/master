@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  <h3>{{ $point->complete_name }}</h3>
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      @if($action=='create')
        {!! Form::hidden('point_id', $point->id) !!}
      @endif
      {!! Segment::form_input($i, 'date', 'text', ['class'=>'datepicker']) !!}
    </div>
    <div class="child">
      <h3>INSUMOS DESTACADOS</h3>
      <div class="table-responsive">
        <table class="table" id="important_products">
          <thead><tr class="title">
            <td>Insumo (*)</td>
            <td>Saldo Anterior (*)</td>
            <td>Pedido Mensual (*)</td>
            <td>Total a Entregar (*)</td>
            <td>Observaciones</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->important_products)>0)
              @foreach($i->important_products as $key => $si)
                @include('item_child.endowment-product', ['name'=>'important_product', 'count'=>$key, 'product_id'=>NULL, 'options'=>$options_important_product])
              @endforeach
            @else
              @foreach($point->important_products as $key => $point_product)
                @include('item_child.endowment-product', ['name'=>'important_product', 'count'=>$key, 'product_id'=>$point_product->product_id, 'options'=>$options_important_product, 'si'=>0])
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="important_products" href="#" data-count="500">Añadir nuevo insumo</a>
    </div>
    <div class="child">
      <h3>INSUMOS</h3>
      <div class="table-responsive">
        <table class="table" id="products">
          <thead><tr class="title">
            <td>Insumo (*)</td>
            <td>Saldo Anterior (*)</td>
            <td>Pedido Mensual (*)</td>
            <td>Total a Entregar (*)</td>
            <td>Observaciones</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->products)>0)
              @foreach($i->products as $key => $si)
                @include('item_child.endowment-product', ['name'=>'product', 'count'=>$key, 'product_id'=>NULL, 'options'=>$options_product])
              @endforeach
            @else
              @foreach($point->normal_products as $key => $point_product)
                @include('item_child.endowment-product', ['name'=>'product', 'count'=>$key, 'product_id'=>$point_product->product_id, 'options'=>$options_product, 'si'=>0])
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="products" href="#" data-count="500">Añadir nuevo insumo</a>
    </div>
    <div class="child">
      <h3>IMPLEMENTOS</h3>
      <div class="table-responsive">
        <table class="table" id="implements">
          <thead><tr class="title">
            <td>Implemento (*)</td>
            <td>Cantidad Solicitada (*)</td>
            <td>Última Entrega (*)</td>
            <td>Cantidad Entregada (*)</td>
            <td>Observaciones</td>
            <td>X</td>
          </tr></thead>
          <tbody>
            @if($action=='edit'&&count($i->implement_products)>0)
              @foreach($i->implement_products as $key => $si)
                @include('item_child.endowment-implement', ['name'=>'implement', 'count'=>$key, 'product_id'=>NULL, 'options'=>$options_implement])
              @endforeach
            @else
                @include('item_child.endowment-implement', ['name'=>'implement', 'count'=>0, 'options'=>$options_implement, 'si'=>0])
            @endif
          </tbody>
        </table>
      </div>
      <a class="agregar_fila" rel="implements" href="#" data-count="500">Añadir nuevo implemento</a>
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection
@section('script')
  @include('scripts.child-js')
@endsection