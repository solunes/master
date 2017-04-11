<div class="page-wrapper page-child">
  <div class="page-content">
    @if(Session::has('message_error'))
      <div class="alert alert-danger center">{{ Session::get('message_error') }}</div>
    @elseif (Session::has('message_success'))
      <div class="alert alert-success center">{{ Session::get('message_success') }}</div>
    @endif
    @yield('content')
  </div>
</div>
@yield('script')