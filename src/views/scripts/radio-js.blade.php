<script type="text/javascript">
  $("input:radio").click(function() {
  	var name = $(this).attr("name");
    if ($(this).val() == 'others') {
    	$('.'+name+'_others').removeClass('hidden');
    } else if ($(this).val() == 'not_satisfied' || $(this).val() == 'average') {
    	$('.'+name+'_explanation').removeClass('hidden');
    } else if ($(this).val() == 'no') {
    	$('.'+name+'_no').removeClass('hidden');
    } else {
    	$('.'+name+'_others').addClass('hidden');
    	$('.'+name+'_explanation').addClass('hidden');
    	$('.'+name+'_no').addClass('hidden');
    }
  });
</script>