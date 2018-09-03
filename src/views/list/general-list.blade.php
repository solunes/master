@extends('master::layouts/admin')

@section('css')
  @include('master::scripts.lightbox-css')
@endsection
@section('content')
  @if(!$pdf)
    {!! AdminList::make_list_header($module, $node, $id, $parent, $appends, count($items), $items_count, $action_nodes) !!}
    @include('master::helpers.filter')
  @endif
  @if(count($items)>0)
    @if(!$pdf)
      {!! $items->appends(request()->except(array('page')))->render() !!}
    @endif
    <table id="general-list" class="admin-table editable-list table table-striped table-bordered table-hover dt-responsive">
      <thead>
        <tr class="title">
          <td>Nº</td>
          {!! AdminList::make_fields($langs, $fields, $action_fields) !!}
        </tr>
      </thead>
      <tbody data-node="{{ $node->name }}">
        @foreach ($items as $key => $item)
          <tr>
            <td class="ineditable">{{ $key+1 }}</td>
            {!! AdminList::make_fields_values_rows($langs, $module, $model, $item, $fields, $field_options, $appends, $action_fields) !!}
          </tr>
        @endforeach
      </tbody>
    </table>
    @if(!$pdf)
      {!! $items->appends(request()->except(array('page')))->render() !!}
    @endif
  @else
    <p>{{ trans('master::admin.no_items') }}</p>
  @endif

  @if(!$pdf&&isset($graphs)&&$graphs&&count($graphs)>0)
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
  @if(!$pdf)
    @include('master::scripts.lightbox-js')
    @include('master::scripts.select-js')
    @if(config('solunes.list_inline_edit'))
      @include('master::scripts.inline-edit-ajax-js')
    @endif
    @include('master::helpers.graph')
  @endif
@endsection