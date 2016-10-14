  <h3>Campos para Filtro</h3>
	@if(isset($fields)&&count($fields)>0)
  	  {!! Form::open(['url'=>'admin/modal-filter', 'method'=>'POST', 'class'=>'form-horizontal filter']) !!}
        <div class="row">
		      {!! Field::form_input(0, 'edit', ['name'=>'select_field','type'=>'select','required'=>false, 'options'=>$fields], ['cols'=>10,'label'=>trans('master::fields.select_field')]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'type','type'=>'hidden','required'=>false], ['value'=>$type]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'category','type'=>'hidden','required'=>false], ['value'=>$category]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'node_id','type'=>'hidden','required'=>false], ['value'=>$node_id]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'category_id','type'=>'hidden','required'=>false], ['value'=>$category_id]) !!}
	      </div>
        {!! Form::submit(trans('master::admin.add_filter'), array('class'=>'btn btn-site')) !!}
  	  {!! Form::close() !!}
  	@else
  		<p>No hay más campos disponibles para agregar.</p>
	@endif