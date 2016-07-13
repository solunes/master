@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  <h3>{{ $point->complete_name }}</h3>
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      @if($action=='create')
        {!! Form::hidden('point_id', $point->id) !!}
      @endif
      {!! Segment::form_input($i, 'schedule_id', 'select', ['options'=>$point->schedules()->get()->lists('name', 'id')]) !!}
      {!! Segment::form_input($i, 'missing_people', 'text') !!}
      {!! Segment::form_input($i, 'delay', 'text') !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection