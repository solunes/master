  <h3>Campos para Filtro</h3>
	@if(isset($fields)&&count($fields)>0)
  	  {!! Form::open(['url'=>'admin/modal-filter', 'method'=>'POST', 'class'=>'form-horizontal filter']) !!}
        <div class="row">
		      {!! Field::form_input(0, 'edit', ['name'=>'select_field','type'=>'select','required'=>true, 'options'=>$fields], ['cols'=>10,'label'=>trans('master::fields.select_field')]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'type','type'=>'hidden','required'=>false], ['value'=>$type]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'category','type'=>'hidden','required'=>false], ['value'=>$category]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'node_id','type'=>'hidden','required'=>false], ['value'=>$node_id]) !!}
          {!! Field::form_input(0, 'edit', ['name'=>'category_id','type'=>'hidden','required'=>false], ['value'=>$category_id]) !!}
	        @foreach($subfields as $subfield => $field_object)
            {!! Field::form_input(0, 'edit', ['name'=>'select_subfield_'.$subfield,'type'=>'select','required'=>true, 'options'=>$field_object['fields']], ['cols'=>10,'label'=>trans('master::fields.select_subfield').': '.$field_object['label']]) !!}
          @endforeach
        </div>
        {!! Form::submit(trans('master::admin.add_filter'), array('class'=>'btn btn-site')) !!}
  	  {!! Form::close() !!}
  	@else
  		<p>No hay más campos disponibles para agregar.</p>
	@endif
  <script type="text/javascript">
    @foreach($subfields as $subfield => $field_object)
      $("#field_select_subfield_{{ $subfield }}").hide();
    @endforeach
    $('#select_field').on('change', function() {
      @foreach($subfields as $subfield => $field_object)
        $("#field_select_subfield_{{ $subfield }}").hide();
      @endforeach
      @foreach($subfields as $subfield => $field_object)
        if(this.value=="{{ $subfield }}_id"){
          $("#field_select_subfield_{{ $subfield }}").show();
        }
      @endforeach
    })
  </script>