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
				    $("#field_{{ $item->field->name }}").slideUp(500);
					if( $( "#{{ $item->trigger_field }}" ).length ) {
				    	var val = $("#{{ $item->trigger_field }}").val();
					    @if($item->trigger_show=='is')
					    if(val=='{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='is_greater')
					    if(val<'{{ $item->trigger_value }}'){
					    @elseif($item->trigger_show=='is_less')
					    if(val>'{{ $item->trigger_value }}'){
					    @else
					    if(val!='{{ $item->trigger_value }}'){
					   	@endif
				    		$("#field_{{ $item->field->name }}").slideDown(500);
				    	}
					} else {
				    	var $field = $("input.field_{{ $item->trigger_field }}.option_{{ $item->trigger_value }}");
					    @if($item->trigger_show=='is')
					    if($field.is(':checked')){
					    @else
					    if($field.not(':checked')){
					   	@endif
				    		$("#field_{{ $item->field->name }}").slideDown(500);
				    	}
					}
				}
			@endforeach
	    @endif
	</script>
@endif