@section('content')
  <div class="main-content main-content-5">
    <div class="container panel-solicitudes">

      <div class="row main_row">
      	<div class="col-sm-9">
          {{ Form::open(array('name' => 'cuenta_password', 'url' => 'cuenta/password', 'class' => 'form-horizontal prevent-double-submit', 'autocomplete' => 'off')) }}
              <h2>Cambiar Contraseña</h2>
              
              <div class="form-group">
              	{{ Form::label('reminder_password', 'Contraseña (*)', array('class'=>'col-sm-6 control-label')) }} 
                  <div class="col-sm-6">{{ Form::password('reminder_password', array('class'=>'form-control', 'placeholder'=>'Contraseña', 'autocomplete'=>'off')) }}</div>
                  <div class="col-sm-offset-6 col-sm-6 error">{{ $errors->first('reminder_password') }}</div>
             </div>
              <div class="form-group">
              	{{ Form::label('reminder_password_confirmation', 'Confirmar Contraseña (*)', array('class'=>'col-sm-6 control-label')) }} 
                  <div class="col-sm-6">{{ Form::password('reminder_password_confirmation', array('class'=>'form-control', 'placeholder'=>'Confirmar Contraseña', 'autocomplete'=>'off')) }}</div>
                  <div class="col-sm-offset-6 col-sm-6 error">{{ $errors->first('reminder_password_confirmation') }}</div>
             </div>
              <div class="form-group">
                 <div class="col-sm-offset-6 col-sm-6">{{ Form::submit('Cambiar Contraseña', array('class'=>'btn btn-solunes')) }}</div>
              </div>
          {{ Form::close() }}
          </div>
      </div>

    </div>
  </div>  
@stop