<script type="text/javascript">
	$(document).ready(function(){
		$(".open_filter_container").click(function(){
			if($(this).data('status')=='closed'){
				$(this).data('status', 'open');
				$(this).html('<i class="fa fa-angle-up"></i> Ocultar Buscador');
		    	$(".filter_container").show(500);
			} else {
				$(this).data('status', 'closed');
				$(this).html('<i class="fa fa-angle-down"></i> Mostrar Buscador');
		    	$(".filter_container").hide(500);
			}
		});
	});
</script>
@if(isset($filters)&&$filters)
  <script type="text/javascript">
	$(document).ready(function(){
	  @foreach($filters as $field_name => $field)
	  	@if($field['subtype']=='date')
		    $('.f_date_{{ $field_name }}').pickadate({
		    	format: 'yyyy-mm-dd',
		    	formatSubmit: 'yyyy-mm-dd',
		    	selectYears: true,
				selectMonths: true,
				min: '{{ $field["first_day"] }}',
				max: '{{ $field["last_day"] }}',
		    });
		@endif
	  @endforeach
	});
  </script>
@endif