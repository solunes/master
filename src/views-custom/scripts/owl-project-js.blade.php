<script type="text/javascript">
    jQuery(document).ready(function($) {
      var owl = jQuery('#owl-projects');
      owl.owlCarousel({
        loop:true,
	    margin:10,
	    nav:true,
	    navText: ['<span class="fa fa-angle-left"></span>','<span class="fa fa-angle-right"></span>'],
	    items: 1,
      });
	  owl.on('changed.owl.carousel', function(event) {
	  	current = event.item.index;
	  	currentIndex = $(event.target).find(".owl-item").eq(current).find(".item").data('count');
        toggleGroup('project-'+currentIndex); 
	  });
    });
</script>