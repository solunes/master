@if($counter==3)
	<div class="col-sm-12 col-xs-12 grid-item">
		<h2><a href="#" class="show-more" data-project="{{ $item->project_id }}">&raquo; Ver más fotografías</a></h2>
	</div>
@endif
@if($counter>2)
<div class="col-sm-4 col-xs-6 grid-item animated hide project-{{ $item->project_id }}">
@else
<div class="col-sm-4 col-xs-6 grid-item animated">
@endif
  <a class="lightbox" href="{{ $full }}">
	<div class="gallery_view">
	  <img class="img-responsive" src="{{ $thumb }}" />
	  <div class="mask">
	    <i class="fa fa-search"></i>
	  </div>
	</div>
  </a>
</div>