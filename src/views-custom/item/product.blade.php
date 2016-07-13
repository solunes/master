@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      {!! Segment::form_input($i, 'code', 'text') !!}
      {!! Segment::form_input($i, 'name', 'text') !!}
      {!! Segment::form_input($i, 'unit', 'text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'type', 'select', ['options'=>$options_type) !!}
      {!! Segment::form_input($i, 'status', 'select', ['options'=>$options_status]) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection