@extends('master::layouts/admin-2')

@section('css')
  <link rel="stylesheet" href="{{ asset('assets/sales/store.css') }}">
  @include('master::scripts.lightbox-css')
@endsection

@section('content')

<div class="content-header-left col-md-9 col-12 mb-2">
  <div class="row breadcrumbs-top">
      <div class="col-12">
          <h2 class="content-header-title float-left mb-0">{{ $node->plural }}</h2>
          <div class="breadcrumb-wrapper col-12">
              <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{ url('inicio') }}">Inicio</a></li>
                  <li class="breadcrumb-item active">{{ $node->plural }}</li>
              </ol>
          </div>
      </div>
  </div>
</div>

{!! Form::open(['url'=>'customer-admin/graphs/'.$node->name, 'method'=>'get']) !!}
  
@if(count($field_options)>0)
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Filtrar {!! $node->plural !!} <a id="select_all" href="#">Agregar Todos</a> - <a id="retire_all" href="#">Quitar Todos</a></h4>
        </div>
        <div class="card-content">
          <div class="card-body">
            <ul class="list-unstyled mb-0" style="text-align: left;">
              @foreach($field_options as $field_id => $field_name)
                <li class="d-inline-block mr-2">
                  <fieldset>
                    <div class="vs-checkbox-con vs-checkbox-primary">
                        {!! Form::checkbox('filter-field-option[]', $field_id, true, ['class'=>'filter-field-group']) !!}
                      <span class="vs-checkbox">
                          <span class="vs-checkbox--check">
                              <i class="vs-icon feather icon-check"></i>
                          </span>
                      </span>
                      <span class="">{!! $field_name !!}</span>
                    </div>
                  </fieldset>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

<div class="row">
  <div class="col-sm-4 col-md-3 col-lg-3">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Tipo de Gráfico</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          {!! Form::select('graph-type', ['graph-bar'=>'Barras','graph-line'=>'Lineas','graph-circle'=>'Torta'], $graph_type, ['class'=>'form-control']) !!}
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Filtrar por Fecha - <a id="select_all_2" href="#">Agregar</a> - <a id="retire_all_2" href="#">Quitar</a></h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          {!! Form::hidden('last_field', $last_field) !!}
          <ul class="list-unstyled mb-0 year-section" style="text-align: left;">
            @if(count($years)>0)
              <ul class="admin-list">
                @foreach($years as $year)
                  <li class="d-inline-block mr-2" style="width: 100%;">
                    <fieldset>
                      <div class="vs-checkbox-con vs-checkbox-primary">
                        {!! Form::checkbox('filter-date[]', $year, true, ['class'=>'filter-year-group']) !!}
                        <span class="vs-checkbox">
                          <span class="vs-checkbox--check">
                            <i class="vs-icon feather icon-check"></i>
                          </span>
                        </span>
                        <span class="">{{ $year }}</span>
                      </div>
                    </fieldset>
                  </li>
                @endforeach
              </ul>
            @endif
          </ul>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Filtrar por Campo</h4>
      </div>
      <div class="card-content">
        <div class="card-body">
          <ul class="list-unstyled mb-0" style="text-align: left;">
            @if(count($fields)>0)
              <ul class="admin-list">
                <li class="d-inline-block mr-2" style="width: 100%;">
                  <fieldset>
                    <div class="vs-checkbox-con vs-checkbox-primary">
                      {!! Form::radio('filter-field', 'total', true) !!}
                      <span class="vs-checkbox">
                        <span class="vs-checkbox--check">
                          <i class="vs-icon feather icon-check"></i>
                        </span>
                      </span>
                      <span class="">Total</span>
                    </div>
                  </fieldset>
                </li>
                @foreach($fields as $field_item)
                  <li class="d-inline-block mr-2" style="width: 100%;">
                    <fieldset>
                      <div class="vs-checkbox-con vs-checkbox-primary">
                        {!! Form::radio('filter-field', $field_item->id, false) !!}
                        <span class="vs-checkbox">
                          <span class="vs-checkbox--check">
                            <i class="vs-icon feather icon-check"></i>
                          </span>
                        </span>
                        <span class="">{{ $field_item->label }}</span>
                      </div>
                    </fieldset>
                  </li>
                @endforeach
              </ul>
            @endif
          </ul>
          {!! Form::hidden('search', 1) !!}
          <button type="submit" class="btn btn-primary btn-site mr-1 mb-1" style="width: 100%">Actualizar</button>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-8 col-md-9 col-lg-9 responsive-graph">
    <div class="graph-container">
      <h5>Reporte: {!! $node->plural !!}</h5>
      <p>Items: <?php echo count($items); ?></p>
      <figure class="highcharts-figure">
          <div id="graph-container"></div>
      </figure>
    </div>
  </div>
</div>
{!! Form::close() !!}  

@endsection


@section('script')
  @if(!$pdf)
    @include('master::scripts.lightbox-js')
    @include('master::scripts.select-js')
    @if(config('solunes.list_inline_edit'))
      @include('master::scripts.inline-edit-ajax-js')
    @endif
  @endif

  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script type="text/javascript">
    $('#select_all').click(function() {
        var checkboxes = $('.filter-field-group');
        checkboxes.prop('checked', 1);
        return false;
    });
    $('#select_all_2').click(function() {
        var checkboxes = $('.filter-year-group');
        checkboxes.prop('checked', 1);
        return false;
    });
    $('#retire_all').click(function() {
        var checkboxes = $('.filter-field-group');
        checkboxes.prop('checked', 0);
        return false;
    });
    $('#retire_all_2').click(function() {
        var checkboxes = $('.filter-year-group');
        checkboxes.prop('checked', 0);
        return false;
    });
  </script>
  @if($graph_type=='graph-bar')
    @include('master::scripts.new-graph-bar-js')
  @elseif($graph_type=='graph-line')
    @include('master::scripts.new-graph-line-js')
  @elseif($graph_type=='graph-circle')
    @include('master::scripts.new-graph-circle-js')
  @endif

@endsection