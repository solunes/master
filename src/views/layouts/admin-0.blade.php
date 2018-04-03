{{ header('X-UA-Compatible: IE=edge,chrome=1') }}
<!doctype html>
<html lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $site->name }} | @yield('title', $site->title)</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="@yield('description', $site->description)" />
  <meta name="keywords" content="{{ $site->keywords }}" />
  <meta name="google-site-verification" content="{{ $site->google_verification }}" />
  <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/img/favicon/favicon-57.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/favicon/favicon-72.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/img/favicon/favicon-114.png') }}">
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link rel="stylesheet" href="{{ url(elixir("assets/css/vendor.css")) }}">
  <link rel="stylesheet" href="{{ url(elixir("assets/css/plugins.css")) }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/master.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
  <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
  @yield('css')
</head>
@if(!$pdf)
  <body class="page-header-fixed page-sidebar-closed-hide-logo page-sidebar-fixed page-content-white admin-site">
    <div class="page-wrapper">
      <!-- BEGIN HEADER -->
      <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
          <!-- BEGIN LOGO -->
          <div class="page-logo">
            <a href="{{ url('') }}">
              <img src="{{ asset('assets/img/logoadmin.png') }}" alt="logo" class="logo-default" /> </a>
            <div class="menu-toggler sidebar-toggler">
              <span></span>
            </div>
          </div>
          <!-- END LOGO -->
          <!-- BEGIN RESPONSIVE MENU TOGGLER -->
          <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
          </a>
          <!-- END RESPONSIVE MENU TOGGLER -->
          <!-- BEGIN TOP NAVIGATION MENU -->
          <div class="top-menu">
            @if(auth()->check()&&auth()->user()->hasPermission('dashboard'))
              <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                  <a href="javascript:;" class="dropdown-toggle dropdown-notifications" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" data-id="{{ json_encode($notifications_ids) }}">
                    <i class="icon-bell"></i>
                    @if($notifications_unread>0)
                      <span class="badge badge-default"> {{ $notifications_unread }} </span>
                    @endif
                  </a>
                  <ul class="dropdown-menu">
                    <li class="external">
                      <h3><span class="bold">
                        {{ count($notifications) }} {{ trans_choice('master::model.notification', count($notifications)) }}
                        @if($notifications_unread>0)
                           {{ trans_choice('master::model.unread', $notifications_unread) }}
                        @endif
                      </span> </h3>
                      <a href="{{ url('admin/my-notifications') }}">Ver todas</a>
                    </li>
                    <li>
                      <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                        @foreach($notifications as $notification)
                          <li @if(!$notification->checked_date) class="unread" @endif data-id="{{ $notification->id }}" >
                            <a @if($notification->url) target="_blank" href="{{ $notification->url }}" @else href="#" @endif >
                              <span class="time">{{ $notification->created_at->format('Y-m-d') }}</span>
                              <span class="details">
                                @if($notification->url) <i class="fa fa-external-link"></i> @endif
                                {{ $notification->notification_messages->where('type', 'dashboard')->first()->message }}
                              </span>
                            </a>
                          </li>
                        @endforeach
                        @if($notifications_unread>count($notifications))
                          <li class="center"><a href="{{ url('admin/my-notifications') }}">Ver {{ $notifications_unread - count($notifications) }} {{ trans_choice('master::model.notification', $notifications_unread - count($notifications)) }} más</a></li>
                        @endif
                      </ul>
                    </li>
                  </ul>
                </li>
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <i class="icon-envelope-open"></i>
                    @if(count($inbox_unread_array)>0)
                        <span class="badge badge-default"> {{ count($inbox_unread_array) }} </span>
                    @endif
                  </a>
                  <ul class="dropdown-menu">
                    <li class="external">
                      <h3>Tienes <span class="bold">{{ count($inbox) }}</span> mensajes</h3>
                          <a href="{{ url('admin/my-inbox') }}">Ver todos</a>
                        </li>
                        <li>
                          <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                            @foreach($inbox as $message)
                              <li @if(in_array($message->id, $inbox_unread_array)) class="unread" @endif >
                                <a href="{{ url('admin/inbox/'.$message->id) }}">
                                  @if(count($message->other_users)==1)
                                    <span class="photo"><img src="{{ asset('assets/admin/img/no_picture.jpg') }}" class="img-circle" alt=""></span>
                                  @else
                                    <span class="photo"><img src="{{ asset('assets/admin/img/no_picture.jpg') }}" class="img-circle" alt=""></span>
                                  @endif
                                  <span class="subject">
                                    <span class="from">
                                      @foreach($message->other_users->take(2) as $key => $user)
                                        @if($key>0)
                                          , 
                                        @endif
                                        {{ $user->user->name }}
                                      @endforeach
                                      @if(count($message->other_users)>2)
                                        ...
                                      @endif
                                    </span>
                                    <span class="time">{{ $message->last_message->created_at->format('H:i') }} </span>
                                  </span>
                                  <span class="message">{{ $message->last_message->message }} </span>
                                </a>
                              </li>
                            @endforeach
                          </ul>
                        </li>
                    </ul>
                </li>
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN TODO DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <i class="icon-calendar"></i>
                    @if(count($alerts)>0)
                      <span class="badge badge-default"> {{ count($alerts) }} </span>
                    @endif
                  </a>
                  <ul class="dropdown-menu extended tasks">
                    <li class="external">
                      <h3><span class="bold">{{ count($alerts) }} indicadores</span> pendientes
                      </h3>
                      <a href="{{ url('admin') }}">Ver todos</a>
                    </li>
                    <li>
                      <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                        @foreach($alerts as $alert)
                        <li>
                          <a href="javascript:;">
                            <span class="task">
                              <span class="desc">{{ $alert->indicator->name }}</span>
                              <span class="percent">{{ ($alert->indicator->value/$alert->goal)*100 }}%</span>
                            </span>
                            <span class="progress">
                              <span style="width: {{ ($alert->indicator->value/$alert->goal)*100 }}%;" class="progress-bar progress-bar-danger" aria-valuenow="{{ ($alert->indicator->value/$alert->goal)*100 }}" aria-valuemin="0" aria-valuemax="100">
                                <span class="sr-only">{{ ($alert->indicator->value/$alert->goal)*100 }}% de la Meta</span>
                              </span>
                            </span>
                          </a>
                        </li>
                        @endforeach
                      </ul>
                    </li>
                  </ul>
                </li>
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <span class="username username-hide-on-mobile"> {{ auth()->user()->name }} </span>
                    <i class="fa fa-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-default">
                    <li>
                      <a href="{{ url('account') }}"><i class="icon-user"></i> Mi Perfil</a>
                    </li>
                    <li>
                      <a href="{{ url('admin/my-inbox') }}">
                        <i class="icon-envelope-open"></i> Mis Mensajes
                        <span class="badge badge-danger"> {{ count($inbox) }} </span>
                      </a>
                    </li>
                    <li>
                      <a href="{{ url('admin') }}">
                        <i class="icon-rocket"></i> Mis Indicadores
                        <span class="badge badge-success"> {{ count($alerts) }} </span>
                      </a>
                    </li>
                    <li class="divider"> </li>
                    <li>
                      <a href="{{ url('auth/logout') }}"><i class="icon-key"></i> Salir</a>
                    </li>
                  </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
              </ul>
            @endif
          </div>
          <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
      </div>
      <!-- END HEADER -->
      <!-- BEGIN HEADER & CONTENT DIVIDER -->
      <div class="clearfix"> </div>
      <!-- END HEADER & CONTENT DIVIDER -->
      <!-- BEGIN CONTAINER -->
      <div class="page-container">
          <!-- BEGIN SIDEBAR -->
          <div class="page-sidebar-wrapper">
              <!-- BEGIN SIDEBAR -->
              <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
              <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
              <div class="page-sidebar navbar-collapse collapse">
                @include('master::includes.menu-logged', ['items'=> $menu_main->roots()])
              </div>
              <!-- END SIDEBAR -->
          </div>
          <!-- END SIDEBAR -->
          <!-- BEGIN CONTENT -->
          <div class="page-content-wrapper">
              <!-- BEGIN CONTENT BODY -->
              <div class="page-content">
                @if(Session::has('message_error'))
                  <div class="alert alert-danger center">{{ Session::get('message_error') }}</div>
                @elseif (Session::has('message_success'))
                  <div class="alert alert-success center">{{ Session::get('message_success') }}</div>
                @endif
                @yield('content')
              </div>
              <!-- END CONTENT BODY -->
          </div>
          <!-- END CONTENT -->
      </div>
      <!-- END CONTAINER -->
      <!-- BEGIN FOOTER -->
      <div class="page-footer">
          <div class="page-footer-inner"> {{ $footer_name.' '.date('Y').' | '.$footer_rights }}
          </div>
          <div class="scroll-to-top">
              <i class="icon-arrow-up"></i>
          </div>
      </div>
      <!-- END FOOTER -->
    </div>
    <!--[if lt IE 9]>
        <script src="{{ url(elixir("assets/js/ie8.js")) }}"></script> 
    <![endif]-->
    <script src="{{ url(elixir("assets/js/vendor.js")) }}"></script>
    <script src="{{ url(elixir("assets/js/plugins.js")) }}"></script>
    <script src="{{ asset('assets/admin/scripts/master.js') }}"></script>
    <script src="{{ asset('assets/admin/scripts/admin.js') }}"></script>
    @include('master::scripts.date-js')
    @include('master::scripts.time-js')
    @include('master::scripts/filter-js')
    @include('master::scripts/table-js')
    @include('master::scripts/notifications-js')
    @yield('script')
  </body>
@else
  <body class="admin-site pdf-site">
    <div class="content-wrap pdf-wrap">
      @yield('content')
    </div>
  </body>
@endif
</html>