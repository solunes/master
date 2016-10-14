@if(in_array('form', $script_array))
  	<link rel="stylesheet" href="{{ asset('assets/admin/css/froala.css') }}">
    @include('master::scripts.lightbox-css')
@endif
@if(in_array('lightbox', $script_array))
    @include('master::scripts.lightbox-css')
@endif