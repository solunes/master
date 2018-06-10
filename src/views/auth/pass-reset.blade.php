@extends('master::layouts/admin')
@section('title', 'Cambiar Contraseña')

@section('content')
<div id="login" class="container">
  {!! Form::open(array('name'=>'password_reset', 'role'=>'form', 'url'=>'password/update', 'class'=>'form-horizontal prevent-double-submit')) !!}
    <h2>Recuperar Contraseña</h2>
    <p>Introduzca su nueva contraseña en ambos campos.</p>
    <div class="form-group">
      {!! Form::label('password', 'Contraseña', ['class'=>'col-sm-3 control-label']) !!} 
      <div class="col-sm-6">
        {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>'Escriba una nueva contraseña']) !!}
      </div>
      <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('password') }}</div>
    </div>
    <div class="form-group">
      {!! Form::label('password_confirmation', 'Confirmar Contraseña', ['class'=>'col-sm-3 control-label']) !!} 
      <div class="col-sm-6">
        {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>'Repita la contraseña introducida']) !!}
      </div>
      <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('password_confirmation') }}</div>
    </div>
    <div class="form-group">
      {!! Form::hidden('token', $token) !!}
      <div class="col-sm-offset-3 col-sm-6">
        {!! Form::submit('Recuperar', ['class'=>'btn btn-site']) !!}
      </div>
    </div>
  {!! Form::close() !!}
</div>
@endsection