@if(isset($conditional_array))
	<script type="text/javascript">
	    @if($conditional_array)
		  	@foreach($conditional_array as $item)
				$(function () {
					applyCond{{ $item->id }}();
					$("select#{{ $item->trigger_field }}").change(applyCond{{ $item->id }});
					$("input.field_{{ $item->trigger_field }}").change(applyCond{{ $item->id }});
				}); 
				function applyCond{{ $item->id }}() {
					// Generar array de valores a ser encontrados
			    	var trigger_array = [
			    	  @foreach(explode(',', $item->trigger_value) as $trigger_value)
			    	    "{{ $trigger_value }}",
			    	  @endforeach
			    	];
			    	var checked = 'false';
				    var trigger_show = "{{ $item->trigger_show }}";
					if( $( "select#{{ $item->trigger_field }}" ).length ) {
				    	var val = $("#{{ $item->trigger_field }}").val();
					    @if($item->trigger_show=='is')
					    if(val=='{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='is_greater')
					    if(val<'{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='is_less')
					    if(val>'{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='is_not')
					    if(val!='{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='where_in')
					    if($.inArray(val, trigger_array)){
					   	@endif
				    		$("#field_{{ $item->field->name }}").slideDown(500);
				    	} else {
				    		$("#field_{{ $item->field->name }}").slideUp(500);
				    	}
					} else {
				    	// Revisar en input si es que los valores existen
						$.each(trigger_array, function(index, value) {
   							if($('input.field_{{ $item->trigger_field }}.option_'+value).is(':checked')) { 
								checked = 'true';
							}
						});
						// Revisar si el campo se muestra o no
						if((trigger_show=='is_not'&&checked=='false')||(trigger_show!='is_not'&&checked=='true')){
				    		$("#field_{{ $item->field->name }}").slideDown(500);
						} else {
				    		$("#field_{{ $item->field->name }}").slideUp(500);
						}
					}
				}
			@endforeach
	    @endif
	</script>
@endif