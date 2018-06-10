@extends('master::layouts/admin')
@section('title', 'Recuperar Contraseña')

@section('content')
<div class="pass-recover container">
  <h2>¿Olvidaste tu Contraseña?</h2>
  <p>Introduce tu cuenta de correo electrónico para que te enviémos los pasos para restablecer tu contraseña y asi puedas acceder nuevamente.</p><br><br>
  {!! Form::open(array('name'=>'password_forget', 'role'=>'form', 'url'=>'password/request', 'class'=>'form-horizontal prevent-double-submit')) !!}
    <div class="form-group">
      {!! Form::label('email', 'eMail', array('class'=>'col-sm-3 control-label')) !!} 
      <div class="col-sm-6">{!! Form::email('email', null, array('class'=>'form-control')) !!}</div>
      <div class="col-sm-offset-3 col-sm-6 error">{{ $errors->first('email') }}</div>
    </div>
    @if(config('solunes.nocaptcha_login'))
      <div class="row"><div class="col-sm-offset-3">
        {!! NoCaptcha::display() !!}
      </div></div>
    @endif
    <div class="row">
      <div class="col-sm-offset-3 col-sm-6">
        {!! Form::submit('Recuperar', array('class'=>'btn btn-site')) !!}
      </div>
    </div>
  {!! Form::close() !!}
</div>
@endsection

@section('script')
  @if(config('solunes.nocaptcha_login'))
    {!! NoCaptcha::renderJs(config('solunes.main_lang')) !!}
  @endif
@endsection