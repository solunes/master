<script type="text/javascript">
	$(function(){
	    var $container = $('.grid');
	    $container.imagesLoaded( function(){
	        $container.isotope({
			  itemSelector: '.grid-item',
			  percentPosition: true,
			  masonry: {
			    columnWidth: '.grid-item'
			  }
	        });
	    });
	});
</script>