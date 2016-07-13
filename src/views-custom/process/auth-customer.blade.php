@extends('layouts/master')

@section('content')
  <h3>Elija un Formulario:</h3>
  <ul class="table-list">
    @foreach($forms as $form)
      <a href="{{ url('quality/filled-form/create/'.$form->id.'?point_id='.$point_id) }}"><li>{{ $form->name }}</li></a>
    @endforeach
  </ul>
@endsection