@if(isset($filters)&&$filters&&(in_array('dates', $filters)||in_array('point', $filters)))
  <script type="text/javascript">
	$(document).ready(function(){
	  @if(in_array('dates', $filters))
	    $('#f_date_from').pickadate({
	    	format: 'yyyy-mm-dd',
	    	formatSubmit: 'yyyy-mm-dd',
	    	selectYears: true,
			selectMonths: true,
			min: '{{ $first_day }}',
			max: '{{ $last_day }}',
	    });
	    $('#f_date_to').pickadate({
	    	format: 'yyyy-mm-dd',
	    	formatSubmit: 'yyyy-mm-dd',
	    	selectYears: true,
			selectMonths: true,
			min: '{{ $first_day }}',
			max: '{{ $last_day }}',
	    });
	  @endif
	  @if(in_array('point', $filters))
      	$(document).on('change', 'select#f_customer', function(){
	        var value = $(this).val();
	        if(value=="any"){
	          var options = [
              	["any", "Cualquiera"],
	          ];
	        }
	        @foreach($customer_object as $customer)
		        else if(value=={{ $customer->id }}){
		          var options = [
		            ["any", "Cualquiera"],
		            @foreach($customer->points as $point)
		              ["{{ $point->id }}", "{!! html_entity_decode($point->name, ENT_NOQUOTES, 'UTF-8') !!}"],
		            @endforeach
		          ];
		        }
		    @endforeach
	        var $el = $("select#f_point");
	        $el.empty(); // remove old options
	        $.each(options, function(i,obj) {
	          $el.append($("<option></option>").attr("value", obj[0]).text(obj[1]));
	        });
      	});
	  @endif
	});
  </script>
@endif