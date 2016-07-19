@extends('layouts/master')

@section('content')
  {!! AdminList::make_list_header($module, $node, $id, $parent, $appends, $action_fields) !!}
  @include('master::helpers.filter')
  @if(count($items)>0)
    <table class="admin-table table table-striped table-bordered dt-responsive">
      <thead>
        <tr class="title">
          {!! AdminList::make_fields($langs, $fields, $action_fields) !!}
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            {!! AdminList::make_fields_values_rows($langs, $module, $model, $item, $fields, $appends, $action_fields) !!}
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p>{{ trans('admin.no_items') }}</p>
  @endif
  @if(isset($graphs)&&$graphs&&count($graphs)>0)
    <div class="row">
      @foreach($graphs as $graph_name => $graph)
        <div class="col-sm-{{ $graph_col_size }}">
          <div id="list-graph-{{ $graph_name }}"></div>
        </div>
      @endforeach
    </div>
  @endif
@endsection
@section('script')
  @include('master::helpers.graph')
@endsection