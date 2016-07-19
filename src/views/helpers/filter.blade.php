@if(isset($filters)&&$filters)  
  {!! Form::open(['url'=>request()->url(), 'method'=>'GET', 'class'=>'form-horizontal filter']) !!}
    <div class="row">
      @foreach($filters as $field_name => $field_parameter)
        @if($field_parameter=='dates')
          {!! Field::form_input($i, $dt, ['name'=>'f_date_from','type'=>'string','required'=>false], ['cols'=>3]) !!}
          {!! Field::form_input($i, $dt, ['name'=>'f_date_to','type'=>'string','required'=>false], ['cols'=>3]) !!}
        @else
          {!! Field::form_input($i, $dt, ['name'=>'f_'.$field_name,'type'=>'select','required'=>false, 'options'=>$filter_options[$field_name]], ['label'=>trans('fields.'.$field_name), 'cols'=>3]) !!}
        @endif
      @endforeach
      @foreach($additional_queries as $key_input => $input)
        {!! Field::form_input($i, $dt, ['name'=>$key_input,'type'=>'hidden','required'=>false], ['value'=>$input]) !!}
      @endforeach
      <div class="col-sm-2">
        <br>{!! Form::submit(trans('admin.filter'), array('class'=>'btn btn-site')) !!}
      </div>
    </div>
    {!! Form::hidden('search', 1) !!}
  {!! Form::close() !!}
@endif