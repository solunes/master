@if(in_array('banner', $script_array))
  @include('master::scripts.banners-js')
@endif
@if(in_array('lightbox', $script_array))
  @include('master::scripts.lightbox-js')
  @include('master::scripts.gallery-view-js')
@endif
@if(in_array('masonry', $script_array))
  @include('master::scripts.masonry-js')
@endif
@if(in_array('owl-project', $script_array))
  @include('scripts.owl-project-js')
@endif
@if(in_array('map', $script_array))
  @include('master::scripts.map-js', $location_array)
@endif
@if(in_array('form', $script_array))
  @include('master::helpers.froala')
  @include('master::scripts.child-js')
  @include('master::scripts.conditionals-js', $form_array)
  @include('master::scripts.upload-js')
  @include('master::scripts.lightbox-js')
  @include('master::scripts.tooltip-js')
  @include('master::scripts.accordion-js')
  @include('master::scripts.leave-form-js')
@endif
@if(in_array('locations-project', $script_array))
  @include('scripts.location-project-js', $location_array)
@endif
@if(in_array('locations-contact', $script_array))
  @include('scripts.location-contact-js', $location_array)
@endif