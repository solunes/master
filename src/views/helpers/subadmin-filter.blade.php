@if(isset($filters)&&$filters)  
  <div class="filter_container">
    {!! Form::open(['url'=>request()->url(), 'method'=>'GET', 'class'=>'form-horizontal filter']) !!}
      <div class="row">
        @foreach($filters as $field_name => $field)
          @if($field['subtype']=='date')
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name.'_from','type'=>'date', 'filter'=>$field['id'],'filter_delete'=>$field['show_delete']], ['label'=>$field['label_from'], 'cols'=>6, 'class'=>'f_date_'.$field_name, 'value'=>$filter_values['f_'.$field_name.'_from']]) !!}
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name.'_to','type'=>'date','filter'=>$field['id'],'filter_delete'=>$field['show_delete']], ['label'=>$field['label_to'], 'cols'=>6, 'class'=>'f_date_'.$field_name, 'value'=>$filter_values['f_'.$field_name.'_to']]) !!}
          @elseif($field['subtype']=='string')
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name.'_action','type'=>'select','filter'=>$field['id'],'filter_delete'=>$field['show_delete'], 'options'=>$filter_string_options, 'required'=>true], ['label'=>$field['label'].' ('.trans('master::fields.action').')', 'cols'=>6, 'value'=>$filter_values['f_'.$field_name.'_action']]) !!}
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name,'type'=>'string','filter'=>$field['id'],'filter_delete'=>$field['show_delete']], ['label'=>$field['label'], 'cols'=>6, 'value'=>$filter_values['f_'.$field_name]]) !!}
          @elseif(count($field['options'])>20)
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name,'type'=>'select','filter'=>$field['id'],'filter_delete'=>$field['show_delete'], 'options'=>$field['options']], ['label'=>$field['label'], 'cols'=>12, 'value'=>$filter_values['f_'.$field_name]]) !!}
          @else
            {!! Field::form_input(NULL, $dt, ['name'=>'f_'.$field_name,'type'=>'checkbox','filter'=>$field['id'],'filter_delete'=>$field['show_delete'], 'options'=>$field['options']], ['label'=>$field['label'], 'cols'=>12, 'value'=>$filter_values['f_'.$field_name]]) !!}
          @endif
        @endforeach
        @foreach($additional_queries as $key_input => $input)
          {!! Field::form_input(NULL, $dt, ['name'=>$key_input,'type'=>'hidden','required'=>false], ['value'=>$input]) !!}
        @endforeach
        <div class="filter_button col-sm-3">
          @if(isset($filters)&&$filters)
            {!! Form::submit(trans('master::admin.search'), array('class'=>'btn btn-site')) !!}
          @endif
        </div>
      </div>
      {!! Form::hidden('search', 1) !!}
    {!! Form::close() !!}
  </div>
@endif
