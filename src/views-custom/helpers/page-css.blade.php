@if(in_array('form', $script_array))
  	<link rel="stylesheet" href="{{ url(elixir("assets/css/froala.css")) }}">
    @include('master::scripts.lightbox-css')
@endif
@if(in_array('lightbox', $script_array))
    @include('master::scripts.lightbox-css')
@endif