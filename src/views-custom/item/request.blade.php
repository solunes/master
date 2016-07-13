@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  <h3>{{ $point->complete_name }}</h3>
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      @if($action=='create')
        {!! Form::hidden('point_id', $point->id) !!}
      @endif
      {!! Segment::form_input($i, 'user_id', 'select', ['options'=>$options_user]) !!}
      {!! Segment::form_input($i, 'status', 'select', ['options'=>$options_status]) !!}
      {!! Segment::form_input($i, 'requirement', 'textarea') !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
  @include('helpers.notifications')
@endsection