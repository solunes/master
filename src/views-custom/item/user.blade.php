@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      {!! Segment::form_input($i, 'role_name', 'select', ['options'=>$roles]) !!}
      {!! Segment::form_input($i, 'name', 'text') !!}
      {!! Segment::form_input($i, 'email', 'text') !!}
      {!! Segment::form_input($i, 'email', 'password') !!}
      {!! Segment::form_input($i, 'status', 'select', ['options'=>$status]) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection