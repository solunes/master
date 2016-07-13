@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  <h3>{{ $point->complete_name }}</h3>
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      @if($action=='create')
        {!! Form::hidden('point_id', $point->id) !!}
      @endif
      {!! Segment::form_input($i, 'operator_id', 'select', ['options'=>$options_operator]) !!}
      {!! Segment::form_input($i, 'status', 'select', ['options'=>$options_status]) !!}
      {!! Segment::form_input($i, 'date', 'text', ['class'=>'datepicker']) !!}
      {!! Segment::form_input($i, 'registered_in', 'text', ['class'=>'timepicker']) !!}
      {!! Segment::form_input($i, 'registered_out', 'text', ['class'=>'timepicker']) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection