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


<!-- Data list view starts -->

<section id="data-thumb-view" class="data-thumb-view-header">
  <div class="match-height">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3>Listado General</h3>

          </div>
          <div class="card-content">
            <div class="card-body">
              @include('master::helpers.subadmin-filter')
              <!-- dataTable starts -->
              <div class="table-responsive">
                @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['create']))
                  <div class="top">
                    <div class="actions action-btns">
                      <div class="btn-group dropdown actions-dropodown"></div>
                      <div class="dt-buttons btn-group">
                        <a href="{{ url('customer-admin/model/'.$model.'/create') }}">
                          <button class="btn btn-outline-primary" tabindex="0" aria-controls="DataTables_Table_0"><span><i class="feather icon-plus"></i> Crear Nuevo</span></button>
                        </a>
                      </div>
                    </div>
                  </div>
                @endif
                @if(!$pdf)
                  {!! $items->appends(request()->except(array('page')))->render() !!}
                @endif
                <table id="general-list" class="admin-table table table table-striped table-bordered table-hover @if(config('solunes.list_horizontal_scroll')=='true') nowrap @else dt-responsive @endif">
                  <thead>
                    <tr class="title" style="font-weight: bold;">
                      <td>Nº</td>
                      {!! AdminList::make_fields($langs, $fields, []) !!}
                      @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['edit']))
                        <td>Editar</td>
                      @endif
                      @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['delete']))
                        <td>Eliminar</td>
                      @endif
                    </tr>
                  </thead>
                  <tbody data-node="{{ $node->name }}">
                    @foreach ($items as $key => $item)
                      <tr>
                        <td class="ineditable">{{ $key+1 }}</td>
                        {!! AdminList::make_fields_values_rows($langs, $module, $model, $item, $fields, $field_options, $appends, []) !!}
                        @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['edit']))
                          <td class="product-price">
                            <a href="{{ url($module.'/model/'.$model.'/edit/'.$item->id) }}" class="btn btn-outline-success" style=" color: #28c76f;">
                              Editar
                            </a>
                          </td>
                        @endif
                        @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['delete']))
                          <td class="product-price">
                            <a href="{{ url($module.'/model/'.$model.'/delete/'.$item->id) }}" class="btn btn-outline-danger" style=" color: #ea5455;">
                                Borrar
                            </a>
                          </td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- dataTable ends -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


@section('script')
  @if(!$pdf)
    @include('master::scripts.lightbox-js')
    @include('master::scripts.select-js')
    @if(config('solunes.list_inline_edit'))
      @include('master::scripts.inline-edit-ajax-js')
    @endif
    @include('master::helpers.graph')
  @endif
@endsection