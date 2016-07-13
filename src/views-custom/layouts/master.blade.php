{{ header('X-UA-Compatible: IE=edge,chrome=1') }}
<!doctype html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $site->name }} | @yield('title', $site->title)</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="@yield('description', $site->description)" />
  <meta name="keywords" content="{{ $site->keywords }}" />
  <meta name="google-site-verification" content="{{ $site->google_verification }}" />
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicon/favicon-57.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicon/favicon-72.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicon/favicon-114.png') }}">
  <link rel="stylesheet" href="{{ url(elixir("assets/css/vendor.css")) }}">
  <link rel="stylesheet" href="{{ url(elixir("assets/css/plugins.css")) }}">
  @yield('css')
  <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
  <base href="{{ $site->root }}"/>
</head>
<body>

  @if($pdf)
  @elseif(Func::check_layout()=='admin')
    <div class="phone-menu-toogle row visible-xs">
      <div class="col-xs-6">
        <h3>Admin</h3>
      </div>
      <div class="col-xs-6 right">
        <button class="dnl-btn-toggle">
          <span class="fa fa-bars"></span>
        </button>
      </div>
    </div>
    <!-- Dash Navbar Left -->
    <div class="dash-navbar-left dnl-visible dnl-hide">
      <div class="logo hidden-xs">
        <img class="img-responsive" src="{{ asset('assets/img/logo.png') }}" />
      </div>
      @include('master::includes.menu-logged', ['items'=> $menu_main->roots()])
    </div>
  @else
    <header class="tz-headerHome tz-homeType1 tz-homeTypeFixed vc_row" data-option="1">
      <div class="container">
        <div class="tzHeaderContainer">
          <h3 class="pull-left tz_logo"><a title="Home" href="{{ url('') }}"><img src="{{ asset('assets/img/logo.png') }}"></a></h3>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#tz-navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>

          <nav class="nav-collapse pull-right tz-menu">
            <ul id="tz-navbar-collapse" class="nav navbar-nav collapse navbar-collapse tz-nav">
              @include('includes.menu', ['items'=> $menu_main->roots()])
            </ul>
          </nav>
        </div>
      </div>
    </header>
  @endif

  @if($pdf)
    <div class="content-wrap pdf-wrap">
  @elseif(Func::check_layout()=='admin')
    <div class="content-wrap dnl-visible" data-effect="dnl-push">
  @else
    <div class="content-wrap">
  @endif
    <div class="content-wrap-2">
      @if(!Func::check_layout()=='admin'||request()->segment('1')!='inicio')
        @yield('banner')
      @endif
      @if(Session::has('message_error'))
        <div class="container"><div class="alert alert-danger center">{{ Session::get('message_error') }}</div></div>
      @elseif (Session::has('message_success'))
        <div class="container"><div class="alert alert-success center">{{ Session::get('message_success') }}</div></div>
      @endif
      @yield('content')
    </div>

    @if(!$pdf)
      <!-- footer start -->
      <footer class="tz-footer tz-footer-type1">
        <div class="tz-backtotop">
          <img src="{{ asset('assets/images/back_top_meetup.png') }}" alt="back_top">
        </div>
        <aside class="widget tzsocial">
          <div class="tzSocial_bg">
            <div class="tzSocial_bg_meetup">
              <span class="meetup_line_left"></span>
                @foreach($social as $social_network)
                  <a class="tzSocial-no" target="_blank" href="{{ $social_network->url }}"><i class="fa fa-{{ $social_network->code }}"></i></a>
                @endforeach
              <span class="meetup_line_right"></span>
            </div>
          </div>
        </aside>
        <div class="tzcopyright">
          <p>{{ $footer_name.' '.date('Y').' | '.$footer_rights }}</p>
        </div>
      </footer>
      <!-- footer end -->
    @endif

  </div>

@if(!$pdf)
  <!-- Scripts -->
  <script src="{{ url(elixir("assets/js/vendor.js")) }}"></script>
  <script src="{{ url(elixir("assets/js/plugins.js")) }}"></script>
  @include('master::scripts.date-js')
  @include('master::scripts.time-js')
  @if(Func::check_layout()=='admin')
    @include('master::scripts/filter-js')
    @include('master::scripts/table-js')
  @endif
  @yield('script')
@endif

</body>
</html>