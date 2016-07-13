@if($page->meta_title)
  @section('title', $page->meta_title)
@else
  @section('title', $page->name) 
@endif
@if($page->meta_description)
  @section('description', $page->meta_description)
@endif
@if($page->image)
  @if($page->id==3)
    @section('image', 'style="height: 550px; background-image: url('.\Asset::get_image_path('page', 'normal', $page->image).');"')
  @else
  	@section('image', 'style="background-image: url('.\Asset::get_image_path('page', 'normal', $page->image).');"')
  @endif
@endif
@section('change-locale', $page->translate()->slug) 