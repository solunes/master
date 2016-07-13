@extends('layouts/master')
@section('title', 'Log In')

@section('content')
  <div class="main-content main-content-2">
    <div class="login">
      <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
          @if($blocked_time==0)
          {!! Form::open(array('name'=>'login_form', 'role'=>'form', 'url'=>'auth/login', 'class'=>'form-horizontal prevent-double-submit')) !!}
            <h2 class="col-sm-offset-3 col-sm-9">LOG IN</h2>
            @if($failed_attempts>0)
              <h3 class="col-sm-offset-3 col-sm-9">Intentos Fallidos de Ingreso: {{ $failed_attempts }}</h3>
            @endif

            <div class="form-group">
              {!! Form::label('email', 'eMail', ['class'=>'col-sm-3 control-label']) !!} 
              <div class="col-sm-6">
                {!! Form::email('email', NULL, ['class'=> 'form-control']) !!}
              </div>
              <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('email') }}</div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Password', ['class'=>'col-sm-3 control-label']) !!} 
              <div class="col-sm-6">
                {!! Form::password('password', ['class'=> 'form-control']) !!}
              </div>
              <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('password') }}</div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-6">
                {!! HTML::link('password/recover', 'Olvidaste tu contraseña?') !!}
            </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-6">
                {!! Form::submit('Log In',['class' => 'btn btn-site']) !!}
              </div>
            </div>
          {!! Form::close() !!}
          @else
            <h2 class="col-sm-offset-1 col-sm-9">Iniciar Sesión</h2>
            <h3 class="col-sm-offset-1 col-sm-9">Cometió muchos intentos fallidos para iniciar sesión, por lo que debe esperar {{ $blocked_time }} minutos para volverlo a intentar.</h3>
            <h3 class="col-sm-offset-1 col-sm-9"><a href="{{ url('auth/login') }}">Recargar página</a> | 
            {!! HTML::link('password/recover', 'Olvidaste tu contraseña?') !!}</h3>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
@endsection