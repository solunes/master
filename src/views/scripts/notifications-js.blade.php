<script type="text/javascript"> 
    $(document.body).on('click', '.dropdown-notifications',function(e){
    	var ids = $(this).data('id');
    	if(ids.length >0){
          $.ajax({
	        url: "{{ url('admin/read-notifications') }}",
	        type: "POST",
	        //dataType: 'json',
	        data: { id: ids },
	        success: function(data) {
	          console.log('success' + data.count);
	          $('.dropdown-notifications').data('id', []);
	        }, error: function(error) {
	          console.log('error' + error);
	        }
          });
    	} else {
	        console.log('empty array');
    	}
	});
</script>