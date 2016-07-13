@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      {!! Segment::form_input($i, 'name','text') !!}
      {!! Segment::form_input($i, 'contact_name','text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'email','text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'phone','text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'observations','textarea', ['required'=>false]) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection