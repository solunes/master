<div class="container">
    <h3>ÚNETE A NOSOTROS</h3>

        {!! Form::open(array('name'=>'contacto_enviar', 'role'=>'form', 'url'=>'process/apply', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'files'=>true)) !!}

            {!! Form::hidden('page_name', $page->name) !!}
            <div class="form-group">
              {!! Form::label('name', trans('form.name').' (*)', array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('name', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('name') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('email', trans('form.email').' (*)', array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('email', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('email') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('city_country', trans('form.city-country'), array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('city_country', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('city_country') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('phone', trans('form.phone'), array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('phone', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('phone') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('languages', trans('admin.languages'), array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('languages', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('languages') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('interest', trans('admin.interest'), array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::text('interest', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('interest') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('motivation', trans('admin.motivation').' (*)', array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::textarea('motivation', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('motivation') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('message', trans('form.message').' (*)', array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::textarea('message', NULL, array('autocomplete'=>'off', 'class'=>'form-control')) !!}
                <div class="error">{{ $errors->first('message') }}</div>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('cv', trans('admin.cv').' (*)', array('class'=>'col-sm-3 control-label')) !!} 
              <div class="col-sm-6">
                {!! Form::file('cv') !!}
                <div class="error">{{ $errors->first('cv') }}</div>
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-offset-3 col-sm-6">
                {!! Form::submit(trans('form.send'), array('class'=>'btn btn-site')) !!}
              </div>
            </div>
        {!! Form::close() !!}
    </div>