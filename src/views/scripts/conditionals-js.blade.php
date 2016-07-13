@if(isset($conditional_array))
	<script type="text/javascript">
	    @if($conditional_array)
		  	@foreach($conditional_array as $item)
				$(function () {
					applyCond{{ $item->id }}();
					$("#{{ $item->trigger_field }}").change(applyCond{{ $item->id }});
				}); 
				function applyCond{{ $item->id }}() {
				    var val = $("#{{ $item->trigger_field }}").val();
				    $("#field_{{ $item->field->name }}").hide();
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
				}
			@endforeach
	    @endif
	</script>
@endif