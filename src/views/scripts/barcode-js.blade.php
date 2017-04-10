<script type="text/javascript"> 
    $('#field_barcode').on('click', 'a.create-barcode',function(e){
    	e.preventDefault();
    	console.log('Crear código de barras');
    	var barcode = $(this).data('barcode');
	    $("#barcode").val(barcode);
	    return false;
    });
	$(document).ready(function() {
	    var pressed = false; 
	    var chars = []; 
	    $(window).keypress(function(e) {
	        if (e.which >= 48 && e.which <= 57) {
	            chars.push(String.fromCharCode(e.which));
	        }
	        if (pressed == false) {
	            setTimeout(function(){
	                if (chars.length >= 10) {
	                    var barcode = chars.join("");
	                    console.log("Barcode Scanned: " + barcode);
	                    // REVISAR SI CODIGO EXISTE EN BASE DE DATOS
	                    $("#barcode").val(barcode);
						$.ajax("{{ url('admin/check-barcode/'.$node->id) }}/" + barcode, {
						    success: function(data) {
		                    	//console.log('Exitoso: ' + data);
	                    		if(data.check){
		                    		window.location.replace("{{ url('admin/redirect-barcode/'.$node->name) }}/" + data.id);
	                    		}
						    },
						    error: function() {
						       //$('#notification-bar').text('An error occurred');
						    }
						});
	                }
	                chars = [];
	                pressed = false;
	            },500);
	        }
	        pressed = true;
	    });
	});
	$("#barcode").keypress(function(e){
	    if ( e.which === 13 ) {
	        console.log("Prevent form submit.");
	        e.preventDefault();
	    }
	});
</script>