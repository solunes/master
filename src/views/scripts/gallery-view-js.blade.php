@include('scripts.masonry-js')
<script type="text/javascript">
	$(document).ready(function(){
	    var galleryView = $('.gallery_view').width();
		$(".gallery_view").mouseenter(function() {
		  $('.mask').css("border-width", (galleryView/2.5)+"px")
		}).mouseleave(function() {
		  $('.mask').css("border-width", "0px")
		});
	});
</script>