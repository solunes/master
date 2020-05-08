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
            {!! AdminList::make_list_header($module, $node, $id, $parent, $appends, count($items), $items_count, $action_nodes) !!}
          </div>
          <div class="card-content">
            <div class="card-body">
              @include('master::helpers.subadmin-filter')
              <!-- dataTable starts -->
              <div class="table-responsive">
                @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['create'])||isset(config('solunes.customer_dashboard_nodes.'.$model)['excel']))
                  <div class="top">
                    <div class="actions action-btns">
                      <div class="btn-group dropdown actions-dropodown"></div>
                      <div class="dt-buttons btn-group">
                        @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['create']))
                          @if($id)
                            <a href="{{ url('customer-admin/model/'.$model.'/create?parent_id='.$id) }}">
                          @else
                            <a href="{{ url('customer-admin/model/'.$model.'/create?level=0') }}">
                          @endif
                            <button class="btn btn-outline-primary" tabindex="0" aria-controls="DataTables_Table_0"><span><i class="feather icon-plus"></i> Crear Nuevo</span></button>
                          </a>
                        @endif
                        @if(isset(config('solunes.customer_dashboard_nodes.'.$model)['excel']))
                          <a href="{{ url('customer-admin/model-list/'.$model.'?download-excel=1') }}">
                            <button class="btn btn-outline-primary" tabindex="0" aria-controls="DataTables_Table_0"><span><i class="feather icon-plus"></i> Descargar Excel</span></button>
                          </a>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
                @if(!$pdf)
                  {!! $items->appends(request()->except(array('page')))->render() !!}
                @endif
                <table class="admin-multilevel-table table table-striped table-bordered table-hover dt-responsive">
                  <thead>
                    <tr class="title">
                      <td>Nº</td>
                      {!! AdminList::make_fields($langs, $fields, $action_fields) !!}
                    </tr>
                  </thead>
                  <tbody data-node="{{ $node->name }}">
                    @foreach ($items as $key => $item)
                      <tr>
                        <td>{{ $key+1 }}</td>
                        {!! AdminList::make_fields_values_rows($langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields) !!}
                      </tr>
                      @if(count($item->children)>0)
                        {!! AdminList::make_child_fields_values_rows(($key+1), $langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields) !!}
                      @endif
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
