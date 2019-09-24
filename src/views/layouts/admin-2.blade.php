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
<body class="vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static admin-site-2 " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="../../../html/ltr/vertical-menu-template/index.html">
                        <div class="brand-logo"></div>
                        <h2 class="brand-text mb-0">{{ $site->name }}</h2>
                    </a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                @include('master::includes.menu-logged-2', ['items'=> $menu_main2->roots()])
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content">

        <!-- BEGIN: Header-->
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu floating-nav navbar-light navbar-shadow">
            <div class="navbar-wrapper">
                <div class="navbar-container content">
                    <div class="navbar-collapse" id="navbar-mobile">
                        <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                            <!--<ul class="nav navbar-nav">
                                <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
                            </ul>
                            <ul class="nav navbar-nav bookmark-icons">
                                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-toggle="tooltip" data-placement="top" title="Todo"><i class="ficon feather icon-check-square"></i></a></li>
                                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-toggle="tooltip" data-placement="top" title="Chat"><i class="ficon feather icon-message-square"></i></a></li>
                                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html" data-toggle="tooltip" data-placement="top" title="Email"><i class="ficon feather icon-mail"></i></a></li>
                                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calender.html" data-toggle="tooltip" data-placement="top" title="Calendar"><i class="ficon feather icon-calendar"></i></a></li>
                            </ul>
                            <ul class="nav navbar-nav">
                                <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon feather icon-star warning"></i></a>
                                    <div class="bookmark-input search-input">
                                        <div class="bookmark-input-icon"><i class="feather icon-search primary"></i></div>
                                        <input class="form-control input" type="text" placeholder="Explore Vuesax..." tabindex="0" data-search="template-list" />
                                        <ul class="search-list"></ul>
                                    </div>
                                </li>
                            </ul>-->
                        </div>
                        <ul class="nav navbar-nav float-right">
                            <!--<li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-us"></i><span class="selected-language">English</span></a>
                                <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#" data-language="en"><i class="flag-icon flag-icon-us"></i> English</a><a class="dropdown-item" href="#" data-language="fr"><i class="flag-icon flag-icon-fr"></i> French</a><a class="dropdown-item" href="#" data-language="de"><i class="flag-icon flag-icon-de"></i> German</a><a class="dropdown-item" href="#" data-language="pt"><i class="flag-icon flag-icon-pt"></i> Portuguese</a></div>
                            </li>-->
                            <!--<li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon feather icon-maximize"></i></a></li>
                            <li class="nav-item nav-search"><a class="nav-link nav-link-search"><i class="ficon feather icon-search"></i></a>
                                <div class="search-input">
                                    <div class="search-input-icon"><i class="feather icon-search primary"></i></div>
                                    <input class="input" type="text" placeholder="Explore Vuesax..." tabindex="-1" data-search="template-list" />
                                    <div class="search-input-close"><i class="feather icon-x"></i></div>
                                    <ul class="search-list"></ul>
                                </div>
                            </li>-->
                            @if(auth()->check())
                            <li class="dropdown dropdown-notification nav-item">
                              <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i>
                                @if($notifications_unread>0)
                                <span class="badge badge-pill badge-primary badge-up">5</span>
                                @endif
                              </a>
                              @if(count($notifications)>0)
                                <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                    <li class="dropdown-menu-header">
                                        <div class="dropdown-header m-0 p-2">
                                            <h3 class="white">{{ count($notifications) }} New</h3><span class="notification-title">App Notifications</span>
                                        </div>
                                    </li>
                                    <li class="scrollable-container media-list">
                                      @foreach($notifications as $notification)
                                        <a class="d-flex justify-content-between" href="javascript:void(0)">
                                            <div class="media d-flex align-items-start">
                                                <div class="media-left"><i class="feather icon-plus-square font-medium-5 primary"></i></div>
                                                <div class="media-body">
                                                    <h6 class="primary media-heading">You have new order!</h6><small class="notification-text"> Are your going to meet me tonight?</small>
                                                </div><small>
                                                    <time class="media-meta" datetime="2015-06-11T18:29:20+08:00">9 hours ago</time></small>
                                            </div>
                                          </a>
                                        @endforeach
                                      </li>
                                    <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="javascript:void(0)">Read all notifications</a></li>
                                </ul>
                              @endif
                            </li>
                            <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                    <div class="user-nav d-sm-flex d-none"><span class="user-name text-bold-600">{{ auth()->user()->name }}</span><span class="user-status">Disponible</span></div><span><img class="round" src="{{ asset('assets/admin/img/no_picture.jpg') }}" alt="avatar" height="40" width="40" /></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                  <a class="dropdown-item" href="{{ url('account/my-account/1354351278') }}"><i class="feather icon-user"></i> Editar Perfil</a>
                                  <!--<a class="dropdown-item" href="#"><i class="feather icon-mail"></i> Inbox</a>
                                  <a class="dropdown-item" href="#"><i class="feather icon-check-square"></i> Tareas</a>
                                  <a class="dropdown-item" href="#"><i class="feather icon-message-square"></i> Chats</a>-->
                                    <div class="dropdown-divider"></div><a class="dropdown-item" href="{{ url('auth/logout') }}"><i class="feather icon-power"></i> Cerrar Sesión</a>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- END: Header-->

        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
              @if(Session::has('message_error'))
                <div class="alert alert-danger center">{{ Session::get('message_error') }}</div>
              @elseif (Session::has('message_success'))
                <div class="alert alert-success center">{{ Session::get('message_success') }}</div>
              @endif
              @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        <p class="clearfix blue-grey lighten-2 mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">&copy; {{ $footer_name.' '.date('Y').' | '.$footer_rights }}</span>
            <span class="float-md-right d-none d-md-block">{{ trans('master::layout.developed_by') }} <a href="http://www.solunes.com" target="_blank"><i class="feather icon-terminal"></i> {{ trans('master::layout.developer') }}</a></span>
            <button class="btn btn-primary btn-icon scroll-top" type="button"><i class="feather icon-arrow-up"></i></button>
        </p>
    </footer>
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