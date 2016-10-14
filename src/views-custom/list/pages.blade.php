@extends('layouts/master')

@section('content')
  {!! Admin::make_list_header($module, $model, $id, $parent, $appends, count($items), $action_fields) !!}
  @include('helpers.filter')
  @if(count($items)>0)
    <table class="admin-table table table-striped table-bordered dt-responsive">
      <thead>
        <tr class="title">
          {!! Admin::make_fields($fields, $action_fields) !!}
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            {!! Admin::make_fields_values_rows($module, $model, $item, $fields, $appends, $action_fields) !!}
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
@endsection