@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  <h3>{{ $point->name }}</h3>
  {!! Form::open(Admin::make_form($module, $model, $action, false)) !!}
    <div class="row">
      @if($action=='create')
        {!! Form::hidden('point_id', $point->id) !!}
      @endif
      {!! Segment::form_input($i, 'user_id', 'select', ['options'=>$options_user]) !!}
      {!! Segment::form_input($i, 'floor_id', 'select', ['options'=>$point->floors()->lists('name', 'id')]) !!}
      {!! Segment::form_input($i, 'created_at', 'text', ['class'=>'datepicker']) !!}
    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection