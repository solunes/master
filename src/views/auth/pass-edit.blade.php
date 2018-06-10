@extends('master::layouts/admin')

@section('content')
  <div class="main-content main-content-5">
    <div class="container panel-solicitudes">

      <div class="row main_row">
      	<div class="col-sm-9">
          {!! Form::open(array('name' => 'cuenta_password', 'url' => 'account/password', 'class' => 'form-horizontal prevent-double-submit', 'autocomplete' => 'off')) !!}
              <h2>Cambiar Contraseña</h2>
              
              <div class="form-group">
              	{!! Form::label('password', 'Contraseña (*)', array('class'=>'col-sm-6 control-label')) !!} 
                <div class="col-sm-6">
                  {!! Form::password('password', array('class'=>'form-control', 'placeholder'=>'Contraseña', 'autocomplete'=>'off')) !!}
                  <div class="error">{{ $errors->first('password') }}</div>
                </div>
             </div>
              <div class="form-group">
              	{!! Form::label('password_confirmation', 'Confirmar Contraseña (*)', array('class'=>'col-sm-6 control-label')) !!} 
                <div class="col-sm-6">
                  {!! Form::password('password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirmar Contraseña', 'autocomplete'=>'off')) !!}
                  <div class="error">{!! $errors->first('password_confirmation') !!}</div>
                </div>
             </div>
              <div class="form-group">
                @if(request()->has('intended_url'))
                  {!! Form::hidden('intended_url', request()->input('intended_url')) !!}
                @endif
                <div class="col-sm-offset-6 col-sm-6">{!! Form::submit('Cambiar Contraseña', array('class'=>'btn btn-solunes')) !!}</div>
              </div>
          {!! Form::close() !!}
          </div>
      </div>

    </div>
  </div>  
@endsection