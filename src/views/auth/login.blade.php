@extends('master::layouts/admin')
@section('title', 'Log In')

@section('content')
  <div class="main-content main-content-2">
    <div class="login">
      <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
          @if($blocked_time==0)
            
          @if(session()->has('confirmation_url')&&session()->get('confirmation_url'))
            <h2>Verificar Email</h2>
            <p>Si desea que volvamos a enviar el email de confirmación a su cuenta de correo, haga click aquí:</p>
            <p><a class="btn btn-site" href="{{ session()->get('confirmation_url') }}">Enviar Email de Confirmación</a><br><br></p>
            <div class="page-bar"></div>
          @endif

          {!! Form::open(array('name'=>'login_form', 'role'=>'form', 'url'=>'auth/login', 'class'=>'form-horizontal prevent-double-submit')) !!}
            <h2 class="col-sm-offset-3 col-sm-9">LOG IN</h2>
            @if($failed_attempts>0)
              <h3 class="col-sm-offset-3 col-sm-9">Intentos Fallidos de Ingreso: {{ $failed_attempts }}</h3>
            @endif

            <div class="form-group">
              {!! Form::label('user', 'eMail / Usuario', ['class'=>'col-sm-3 control-label']) !!} 
              <div class="col-sm-6">
                {!! Form::text('user', NULL, ['class'=> 'form-control']) !!}
              </div>
              <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('user') }}</div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Contraseña', ['class'=>'col-sm-3 control-label']) !!} 
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
            @if(config('solunes.nocaptcha_login'))
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                  {!! NoCaptcha::display() !!}
                </div>
              </div>
            @endif
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

          <div class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-6">
                @if(config('solunes.socialite_google'))
                  <a href="{{ url('/auth/google') }}" class="auth-btn auth-btn-google"><button class="btn btn-site"><i class="fa fa-google"></i> Google Plus</button></a>
                @endif
                @if(config('solunes.socialite_facebook'))
                  <a href="{{ url('/auth/facebook') }}" class="auth-btn auth-btn-facebook"><button class="btn btn-site"><i class="fa fa-facebook"></i> Facebook</button></a>
                @endif
                @if(config('solunes.socialite_twitter'))
                  <a href="{{ url('/auth/twitter') }}" class="auth-btn auth-btn-twitter"><button class="btn btn-site"><i class="fa fa-twitter"></i> Twitter</button></a>
                @endif
                @if(config('solunes.socialite_github'))
                  <a href="{{ url('/auth/github') }}" class="auth-btn auth-btn-github"><button class="btn btn-site"><i class="fa fa-github"></i> GitHub</button></a>
                @endif
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if(config('solunes.nocaptcha_login'))
    {!! NoCaptcha::renderJs(config('solunes.main_lang')) !!}
  @endif
@endsection