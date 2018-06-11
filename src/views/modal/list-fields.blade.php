  <h3>Campos Disponibles</h3>
	@if(isset($fields)&&count($fields)>0)
  	  {!! Form::open(['url'=>'admin/edit-list', 'method'=>'POST', 'class'=>'form-horizontal filter']) !!}
        <div class="row">
          @foreach($fields as $field)
		        {!! Field::form_input(0, 'edit', ['name'=>$field['name'],'type'=>'select','required'=>true, 'options'=>$options], ['cols'=>6,'label'=>$field['label'], 'value'=>$field['value']]) !!}
          @endforeach
          {!! Form::hidden('node_name', $node_name) !!}
        </div>
        {!! Form::submit(trans('master::admin.save'), array('class'=>'btn btn-site')) !!}
  	  {!! Form::close() !!}
  	@else
  		<p>No hay más campos disponibles para agregar.</p>
	@endif