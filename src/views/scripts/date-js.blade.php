<script type="text/javascript">
	$(document).ready(function(){
	    $('.datepicker').pickadate({
	    	format: 'yyyy-mm-dd',
	    	formatSubmit: 'yyyy-mm-dd',
	    	selectYears: 10,
			selectMonths: true,
	    });
	    $('.datepicker-max').pickadate({
	    	format: 'yyyy-mm-dd',
	    	formatSubmit: 'yyyy-mm-dd',
	    	selectYears: 40,
			selectMonths: true,
			max: true,
	    });
	    $('.datepicker-min').pickadate({
	    	format: 'yyyy-mm-dd',
	    	formatSubmit: 'yyyy-mm-dd',
	    	selectYears: 10,
			selectMonths: true,
			min: true,
	    });
	});
</script>