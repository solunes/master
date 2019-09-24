{{ header('X-UA-Compatible: IE=edge,chrome=1') }}
<!doctype html>
<html class="loading" lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $site->name }} | @yield('title', $site->title)</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="@yield('description', $site->description)" />
  <meta name="keywords" content="{{ $site->keywords }}" />
  <meta name="google-site-verification" content="{{ $site->google_verification }}" />
  @if(!$pdf)
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicon/favicon-57.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicon/favicon-72.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicon/favicon-114.png') }}">

  <!--begin::Web font -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">
  @endif

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-2.css') }}">
  @if(!$pdf)
  <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
  @else
  <style>
    input.form-control, input.form-control[disabled], .form-control[disabled], textarea { width: 100%; color: #000 !important; }
    select.form-control, select.form-control[disabled] { width: 100%; color: #555 !important; }
    h4 { margin-top: 20px; font-weight: bold; font-size: 16px; }
  </style>
  @endif
  @yield('css')
</head>
@if(!$pdf)
<body class="vertical-layout vertical-menu-modern 1-column  navbar-floating footer-static bg-full-screen-image  blank-page blank-page admin-site-2" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">

    <!-- BEGIN: Content-->
    <div class="app-content content">

        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
              <div class="container">
                  @if(Session::has('message_error'))
                    <div class="alert alert-danger center">{{ Session::get('message_error') }}</div>
                  @elseif (Session::has('message_success'))
                    <div class="alert alert-success center">{{ Session::get('message_success') }}</div>
                  @endif
              </div>
              @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- END: Footer-->
  <script src="{{ asset('assets/admin/scripts/admin-2.js') }}"></script>
  @yield('script')
</body>
@else
<body class="admin-site pdf-site">
  <div class="content-wrap pdf-wrap">
    @yield('content')
  </div>
  @yield('script')
</body>
@endif
</html>