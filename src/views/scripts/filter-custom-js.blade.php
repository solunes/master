@if(isset($custom_options))
	<script type="text/javascript">  
	  $(document).ready(function(){
	    $('#custom-options').on('change', function() {
	      var val = $(this).val();
	      var url = "{{ url('admin/modal-filter/'.$filter_category.'/'.$filter_type.'/'.$filter_category_id) }}/"+ val +"?lightbox[width]=500&lightbox[height]=400";
	      $("#filter-button").attr("href", url);
	    });
	  });
	</script>
@endif