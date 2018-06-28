@if((isset($fields)&&count($fields)>0)||(isset($related_fields)&&count($related_fields)>0))
{!! Form::open(['url'=>'admin/edit-list', 'method'=>'POST', 'class'=>'form-horizontal filter']) !!}
  @if(isset($fields)&&count($fields)>0)
    <h3>Campos Principales Disponibles</h3>
    <div class="row">
      @foreach($fields as $field)
        {!! Field::form_input(0, 'edit', ['name'=>$field['name'],'type'=>'select','required'=>true, 'options'=>$options], ['cols'=>6,'label'=>$field['label'], 'value'=>$field['value']]) !!}
      @endforeach
    </div>
  @endif
  @if(isset($relation_fields)&&count($relation_fields)>0)
    <br><br><h3>Campos Relacionables</h3>
    @foreach($relation_fields as $label => $relation_subfields)
      <br><h4>{{ $label }}</h4>
      <div class="row">
        @foreach($relation_subfields as $relation_subfield)
          {!! Field::form_input(0, 'edit', ['name'=>$relation_subfield['name'],'type'=>'select','required'=>true, 'options'=>$relation_options], ['cols'=>6,'label'=>$relation_subfield['label'], 'value'=>$relation_subfield['value']]) !!}
        @endforeach
      </div>
    @endforeach
  @endif
  {!! Form::hidden('node_name', $node_name) !!}
  {!! Form::submit(trans('master::admin.save'), array('class'=>'btn btn-site')) !!}
{!! Form::close() !!}
@endif