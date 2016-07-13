@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, true)) !!}
    <div class="row">
      {!! Segment::form_input($i, 'name', 'text') !!}
      {!! Segment::form_input($i, 'ci', 'text') !!}
      {!! Segment::form_input($i, 'city_id', 'select', ['options'=>$options_city]) !!}
      {!! Segment::form_input($i, 'birth_day', 'text', ['class'=>'datepicker']) !!}
      {!! Segment::form_input($i, 'phone', 'text', ['required'=>false]) !!}
      {!! Segment::form_input($i, 'picture', 'image', ['folder'=>'operator-picture', 'required'=>false]) !!}
      {!! Segment::form_input($i, 'observations', 'textarea', ['required'=>false]) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection