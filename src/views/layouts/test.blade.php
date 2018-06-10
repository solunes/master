<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicon/favicon-57.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicon/favicon-72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicon/favicon-114.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
    <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
  </head>
  <body class="error-site">
    <div class="container">
      <div class="top">
        <img src="{{ asset('assets/img/logo.png') }}" />
        <div class="top_title">{{ trans('master::admin.error_title') }}</div>
      </div>
      <div class="content">
        <div class="title">
          @yield('title')
        </div>
        <div class="subtitle">
          @yield('description')
        </div>
        <a href="{{ url('') }}">
          <div class="button">
            {{ trans('master::admin.error_button') }}
          </div>
        </a>
      </div>
    </div>
  </body>
</html>