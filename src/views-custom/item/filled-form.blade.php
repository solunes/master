@extends('layouts/master')

@section('content')
  {!! Admin::make_item_header($i, $module, $model, $action, $parent_id) !!}
  {!! Form::open(Admin::make_form($module, $model, $action, true)) !!}
    <div class="row">
      @if($action=='edit'||Auth::user()->hasRole(['admin', 'gerente_general', 'gerente', 'jefe_operaciones_nacional']))
        {!! Segment::form_input($i, 'user_id', 'select', ['options'=>$options_user]) !!}
      @else
        {!! Form::hidden('user_id', Auth::user()->id) !!}
      @endif
      @if($action=='create'&&request()->has('point_id'))
        {!! Form::hidden('point_id', request()->input('point_id')) !!}
      @else
        {!! Segment::form_input($i, 'point_id', 'select', ['options'=>$options_point]) !!}
      @endif
      {!! Form::hidden('form_id', $parent_id) !!}
      {!! Segment::form_inputs_generator($i, $parent_id, 'fields') !!}

    </div>
    {!! Segment::form_submit($i, $action) !!}
  {!! Form::close() !!}
@endsection