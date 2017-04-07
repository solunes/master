@extends('master::layouts/admin')

@section('css')
  @include('master::scripts.lightbox-css')
@endsection
@section('content')
  {!! AdminList::make_list_header($module, $node, $id, $parent, $appends, count($items), $items_count, $action_nodes) !!}
  @include('master::helpers.filter')
  @if(count($items)>0)
    {!! $items->render() !!}
    <table class="admin-table table table-striped table-bordered table-hover dt-responsive">
      <thead>
        <tr class="title">
          <td>Nº</td>
          {!! AdminList::make_fields($langs, $fields, $action_fields) !!}
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $key => $item)
          <tr>
            <td>{{ $key+1 }}</td>
            {!! AdminList::make_fields_values_rows($langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields) !!}
          </tr>
        @endforeach
      </tbody>
    </table>
    {!! $items->render() !!}
  @else
    <p>{{ trans('master::admin.no_items') }}</p>
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
  @include('master::scripts.lightbox-js')
  @include('master::helpers.graph')
@endsection