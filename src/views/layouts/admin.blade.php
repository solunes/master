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

  <!--begin::Web font -->
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
  <script>
        WebFont.load({
          google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700","Asap+Condensed:500"]},
          active: function() {
              sessionStorage.fonts = true;
          }
        });
  </script>
  <!--end::Web font -->

  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/master.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
  <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
  @yield('css')
</head>
@if(!$pdf)
<body class="m-page--fluid m-page--loading-enabled m-page--loading m-header--fixed m-header--fixed-mobile m-footer--push m-aside--offcanvas-default admin-site">
  <div class="m-page-loader m-page-loader--base">
    <div class="m-blockui">
      <span>Por favor espere...</span>
      <span><div class="m-loader m-loader--brand"></div></span>
    </div>
  </div>
  <div class="m-grid m-grid--hor m-grid--root m-page">
    <header id="m_header" class="m-grid__item   m-header "  m-minimize="minimize" m-minimize-mobile="minimize" m-minimize-offset="10" m-minimize-mobile-offset="10" >
      <div class="m-header__top">
        <div class="m-container m-container--fluid m-container--full-height m-page__container">
          <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- begin::Brand -->
            <div class="m-stack__item m-brand m-stack__item--left">
              <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                  <a href="index.html" class="m-brand__logo-wrapper">
                    <img alt="" src="{{ asset('assets/img/logoadmin.png') }}" class="m-brand__logo-default"/>
                    <img alt="" src="{{ asset('assets/img/logoadmin.png') }}" class="m-brand__logo-inverse"/>
                  </a>
                </div>
                <div class="m-stack__item m-stack__item--middle m-brand__tools">
                  <!-- begin::Responsive Header Menu Toggler-->
                  <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                    <span></span>
                  </a>
                  <!-- end::Responsive Header Menu Toggler-->
                  <!-- begin::Topbar Toggler-->
                  <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                    <i class="flaticon-more"></i>
                  </a>
                  <!--end::Topbar Toggler-->
                </div>
              </div>
            </div>
            <!-- end::Brand -->   
            <!--begin::Search-->
            <div class="m-stack__item m-stack__item--middle m-dropdown m-dropdown--arrow m-dropdown--large m-dropdown--mobile-full-width m-dropdown--align-right m-dropdown--skin-light m-header-search m-header-search--expandable- m-header-search--skin-" id="m_quicksearch" m-quicksearch-mode="default">
              <!--begin::Search Form -->
              <form class="m-header-search__form">
                <div class="m-header-search__wrapper">
                  <span class="m-header-search__icon-search" id="m_quicksearch_search">
                    <i class="la la-search"></i>
                  </span>
                  <span class="m-header-search__input-wrapper">
                    <input autocomplete="off" type="text" name="q" class="m-header-search__input" value="" placeholder="Buscar..." id="m_quicksearch_input">
                  </span>
                  <span class="m-header-search__icon-close" id="m_quicksearch_close">
                    <i class="la la-remove"></i>
                  </span>
                  <span class="m-header-search__icon-cancel" id="m_quicksearch_cancel">
                    <i class="la la-remove"></i>
                  </span>
                </div>
              </form>
              <!--end::Search Form -->
              <!--begin::Search Results -->
              <div class="m-dropdown__wrapper">
                <div class="m-dropdown__arrow m-dropdown__arrow--center"></div>
                <div class="m-dropdown__inner">
                  <div class="m-dropdown__body">
                    <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-max-height="300" data-mobile-max-height="200">
                      <div class="m-dropdown__content m-list-search m-list-search--skin-light"></div>
                    </div>
                  </div>
                </div>
              </div>
              <!--end::Search Results -->
            </div>
            <!--end::Search-->
            <!-- begin::Topbar -->
            @if(auth()->check())
            <div class="m-stack__item m-stack__item--right m-header-head" id="m_header_nav">
              <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                <div class="m-stack__item m-topbar__nav-wrapper">
                  <ul class="m-topbar__nav m-nav m-nav--inline">
                    @if(auth()->user()->hasPermission('dashboard'))
                    <li class="m-nav__item m-topbar__notifications m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center  m-dropdown--mobile-full-width" m-dropdown-toggle="click" m-dropdown-persistent="1">
                      <a href="#" class="m-nav__link m-dropdown__toggle" @if($notifications_unread>0) id="m_topbar_notification_icon" @endif >
                        @if($notifications_unread>0)
                          <span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>
                        @endif
                        <span class="m-nav__link-icon">
                          <span class="m-nav__link-icon-wrapper">
                            <i class="flaticon-music-2"></i>
                          </span>
                        </span>
                      </a>
                      <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                        <div class="m-dropdown__inner">
                          <div class="m-dropdown__header m--align-center">
                            <span class="m-dropdown__header-title">
                              {{ count($notifications) }}
                            </span>
                            <span class="m-dropdown__header-subtitle">
                              Notificaciones
                            </span>
                          </div>
                          <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                              <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
                                <li class="nav-item m-tabs__item">
                                  <a class="nav-link m-tabs__link active" data-toggle="tab" href="#topbar_notifications_notifications" role="tab">
                                    Alertas
                                  </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                  <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events" role="tab">
                                    Eventos
                                  </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                  <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_logs" role="tab">
                                    Logs
                                  </a>
                                </li>
                              </ul>
                              <div class="tab-content">
                                <div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
                                  @if(count($notifications)>0)
                                  <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                      <div class="m-list-timeline__items">
                                        @foreach($notifications as $notification)
                                        <div class="m-list-timeline__item @if($notification->checked_date) m-list-timeline__item--read @endif ">
                                          <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                          <span class="m-list-timeline__text">
                                            {{ $notification->notification_messages->where('type', 'dashboard')->first()->message }}
                                            @if($notification->url)
                                               - <a target="_blank" href="{{ $notification->url }}">Abrir</a>
                                            @endif
                                          </span>
                                          <span class="m-list-timeline__time">
                                            {{ $notification->created_at->format('d/m/y') }}
                                          </span>
                                        </div>
                                        @endforeach
                                      </div>
                                    </div>
                                  </div>
                                  @else
                                  <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                    <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                      <span class="">
                                        Todo en orden
                                        <br>
                                        No hay notificaciones
                                      </span>
                                    </div>
                                  </div>
                                  @endif
                                </div>
                                <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                                  @if(count($notifications)>0)
                                  <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                      <div class="m-list-timeline__items">
                                        @foreach($notifications as $notification)
                                        <div class="m-list-timeline__item @if($notification->checked_date) m-list-timeline__item--read @endif ">
                                          <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                          <span class="m-list-timeline__text">
                                            {{ $notification->notification_messages->where('type', 'dashboard')->first()->message }}
                                            @if($notification->url)
                                               - <a target="_blank" href="{{ $notification->url }}">Abrir</a>
                                            @endif
                                          </span>
                                          <span class="m-list-timeline__time">
                                            {{ $notification->created_at->format('d/m/y') }}
                                          </span>
                                        </div>
                                        @endforeach
                                      </div>
                                    </div>
                                  </div>
                                  @else
                                  <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                    <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                      <span class="">
                                        Todo en orden
                                        <br>
                                        No hay notificaciones
                                      </span>
                                    </div>
                                  </div>
                                  @endif
                                </div>
                                <div class="tab-pane" id="topbar_notifications_logs" role="tabpanel">
                                  @if(count($notifications)>0)
                                  <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                    <div class="m-list-timeline m-list-timeline--skin-light">
                                      <div class="m-list-timeline__items">
                                        @foreach($notifications as $notification)
                                        <div class="m-list-timeline__item @if($notification->checked_date) m-list-timeline__item--read @endif ">
                                          <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                          <span class="m-list-timeline__text">
                                            {{ $notification->notification_messages->where('type', 'dashboard')->first()->message }}
                                            @if($notification->url)
                                               - <a target="_blank" href="{{ $notification->url }}">Abrir</a>
                                            @endif
                                          </span>
                                          <span class="m-list-timeline__time">
                                            {{ $notification->created_at->format('d/m/y') }}
                                          </span>
                                        </div>
                                        @endforeach
                                      </div>
                                    </div>
                                  </div>
                                  @else
                                  <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                    <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                      <span class="">
                                        Todo en orden
                                        <br>
                                        No hay notificaciones
                                      </span>
                                    </div>
                                  </div>
                                  @endif
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    @endif
                    @if(auth()->user()->isAdmin())
                    <li class="m-nav__item m-topbar__quick-actions m-dropdown m-dropdown--skin-light m-dropdown--large m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"  m-dropdown-toggle="click">
                      <a href="#" class="m-nav__link m-dropdown__toggle">
                        <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                        <span class="m-nav__link-icon">
                          <span class="m-nav__link-icon-wrapper">
                            <i class="flaticon-share"></i>
                          </span>
                        </span>
                      </a>
                      <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="m-dropdown__inner">
                          <div class="m-dropdown__header m--align-center">
                            <span class="m-dropdown__header-title">
                              Acciones Rápidas
                            </span>
                            <span class="m-dropdown__header-subtitle">
                              Accesos Directos
                            </span>
                          </div>
                          <div class="m-dropdown__body m-dropdown__body--paddingless">
                            <div class="m-dropdown__content">
                              <div class="m-scrollable" data-scrollable="false" data-max-height="380" data-mobile-max-height="200">
                                <div class="m-nav-grid m-nav-grid--skin-light">
                                  <div class="m-nav-grid__row">
                                    <a href="{{ url('admin') }}" class="m-nav-grid__item">
                                      <i class="m-nav-grid__icon flaticon-file"></i>
                                      <span class="m-nav-grid__text">
                                        Dashboard
                                      </span>
                                    </a>
                                    <a href="{{ url('admin/model-list/user') }}" class="m-nav-grid__item">
                                      <i class="m-nav-grid__icon flaticon-time"></i>
                                      <span class="m-nav-grid__text">
                                        Usuarios
                                      </span>
                                    </a>
                                  </div>
                                  <div class="m-nav-grid__row">
                                    <a href="{{ url('admin/import-nodes') }}" class="m-nav-grid__item">
                                      <i class="m-nav-grid__icon flaticon-folder"></i>
                                      <span class="m-nav-grid__text">
                                        Subir en Masa
                                      </span>
                                    </a>
                                    <a href="{{ url('admin/model-list/contact-form') }}" class="m-nav-grid__item">
                                      <i class="m-nav-grid__icon flaticon-clipboard"></i>
                                      <span class="m-nav-grid__text">
                                        Formularios de Contacto
                                      </span>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    @endif
                    <li class="m-nav__item m-topbar__user-profile  m-dropdown m-dropdown--medium m-dropdown--arrow  m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" m-dropdown-toggle="click">
                      <a href="#" class="m-nav__link m-dropdown__toggle">
                        <span class="m-topbar__userpic">
                          <img src="{{ asset('assets/admin/img/no_picture.jpg') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                        </span>
                        <span class="m-nav__link-icon m-topbar__usericon  m--hide">
                          <span class="m-nav__link-icon-wrapper">
                            <i class="flaticon-user-ok"></i>
                          </span>
                        </span>
                        <span class="m-topbar__username m--hide">
                          {{ auth()->user()->name }}
                        </span>
                      </a>
                      <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="m-dropdown__inner">
                          <div class="m-dropdown__header m--align-center">
                            <div class="m-card-user m-card-user--skin-light">
                              <div class="m-card-user__pic">
                                <img src="{{ asset('assets/admin/img/no_picture.jpg') }}" class="m--img-rounded m--marginless" alt=""/>
                              </div>
                              <div class="m-card-user__details">
                                <span class="m-card-user__name m--font-weight-500">
                                  {{ auth()->user()->name }}
                                </span>
                                <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                  {{ auth()->user()->email }}
                                </a>
                              </div>
                            </div>
                          </div>
                          <div class="m-dropdown__body">
                            <div class="m-dropdown__content">
                              <ul class="m-nav m-nav--skin-light">
                                <li class="m-nav__section m--hide">
                                  <span class="m-nav__section-text">
                                    Secciones
                                  </span>
                                </li>
                                <li class="m-nav__item">
                                  <a href="{{ url('account') }}" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-profile-1"></i>
                                    <span class="m-nav__link-title">
                                      <span class="m-nav__link-wrap">
                                        <span class="m-nav__link-text">
                                          Mi Perfil
                                        </span>
                                        <!--<span class="m-nav__link-badge">
                                          <span class="m-badge m-badge--success">
                                            2
                                          </span>
                                        </span>-->
                                      </span>
                                    </span>
                                  </a>
                                </li>
                                <li class="m-nav__item">
                                  <a href="{{ url('admin/my-inbox') }}" class="m-nav__link">
                                    <i class="m-nav__link-icon flaticon-share"></i>
                                    <span class="m-nav__link-text">
                                      Mis Mensajes
                                        @if(count($inbox_unread_array)>0)
                                        <span class="m-nav__link-badge">
                                          <span class="m-badge m-badge--success">
                                            {{ count($inbox_unread_array) }}
                                          </span>
                                        </span>
                                      @endif
                                    </span>
                                  </a>
                                </li>
                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                <li class="m-nav__item">
                                  <a href="{{ url('auth/logout') }}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                    Cerrar Sesión
                                  </a>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            @endif
            <!-- end::Topbar -->
          </div>
        </div>
      </div>
      <div class="m-header__bottom">
        <div class="m-container m-container--fluid m-container--full-height m-page__container">
          <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- begin::Horizontal Menu -->
            <div class="m-stack__item m-stack__item--fluid m-header-menu-wrapper">
              <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
                <i class="la la-close"></i>
              </button>
              <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light "  >
                <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                  @include('master::includes.menu-logged', ['items'=> $menu_main->roots()])
                </ul>
              </div>
            </div>
            <!-- end::Horizontal Menu -->
          </div>
        </div>
      </div>
    </header>
    <!-- end::Header -->  
    <!-- begin::Body -->
    <div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver-desktop m-grid--desktop m-page__container m-body">
      <div class="m-grid__item m-grid__item--fluid m-wrapper">

        <div class="m-content">
          @if(Session::has('message_error'))
            <div class="alert alert-danger center">{{ Session::get('message_error') }}</div>
          @elseif (Session::has('message_success'))
            <div class="alert alert-success center">{{ Session::get('message_success') }}</div>
          @endif
          @yield('content')
        </div>
      </div>
    </div>
    <!-- end::Body -->
    <!-- begin::Footer -->
    <footer class="m-grid__item m-footer ">
      <div class="m-container m-container--fluid m-container--full-height m-page__container">
        <div class="m-footer__wrapper">
          <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
            <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
              <span class="m-footer__copyright">
                {{ $footer_name.' '.date('Y').' | '.$footer_rights }} | Desarrollado por: 
                <a target="_blank" href="http://www.solunes.com" style="color: #fff;">
                  Solunes Digital
                </a>
              </span>
            </div>
            <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
              <ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
                <!--<li class="m-nav__item">
                  <a href="#" class="m-nav__link">
                    <span class="m-nav__link-text">
                      About
                    </span>
                  </a>
                </li>-->
                <li class="m-nav__item">
                  <a href="#" class="m-nav__link" data-toggle="m-tooltip" title="Ayuda: Muy Pronto" data-placement="left">
                    <i class="m-nav__link-icon flaticon-info m--icon-font-size-lg3"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- end::Footer -->

  </div>

  <!-- begin::Quick Sidebar -->
  <!--<div id="m_quick_sidebar" class="m-quick-sidebar m-quick-sidebar--tabbed m-quick-sidebar--skin-light">
    <div class="m-quick-sidebar__content m--hide">
      <span id="m_quick_sidebar_close" class="m-quick-sidebar__close">
        <i class="la la-close"></i>
      </span>
      <ul id="m_quick_sidebar_tabs" class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
        <li class="nav-item m-tabs__item">
          <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_quick_sidebar_tabs_messenger" role="tab">
            Messages
          </a>
        </li>
        <li class="nav-item m-tabs__item">
          <a class="nav-link m-tabs__link"    data-toggle="tab" href="#m_quick_sidebar_tabs_settings" role="tab">
            Settings
          </a>
        </li>
        <li class="nav-item m-tabs__item">
          <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_quick_sidebar_tabs_logs" role="tab">
            Logs
          </a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active m-scrollable" id="m_quick_sidebar_tabs_messenger" role="tabpanel">
          <div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">
            <div class="m-messenger__messages">
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--in">
                  <div class="m-messenger__message-pic">
                    <img src="{{ asset('assets/admin/img/user.jpg') }}" alt=""/>
                  </div>
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-username">
                        Megan wrote
                      </div>
                      <div class="m-messenger__message-text">
                        Hi Bob. What time will be the meeting ?
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--out">
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-text">
                        Hi Megan. It's at 2.30PM
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--in">
                  <div class="m-messenger__message-pic">
                    <img src="{{ asset('assets/admin/img/user.jpg') }}" alt=""/>
                  </div>
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-username">
                        Megan wrote
                      </div>
                      <div class="m-messenger__message-text">
                        Will the development team be joining ?
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--out">
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-text">
                        Yes sure. I invited them as well
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__datetime">
                2:30PM
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--in">
                  <div class="m-messenger__message-pic">
                    <img src="{{ asset('assets/admin/img/user.jpg') }}"  alt=""/>
                  </div>
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-username">
                        Megan wrote
                      </div>
                      <div class="m-messenger__message-text">
                        Noted. For the Coca-Cola Mobile App project as well ?
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--out">
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-text">
                        Yes, sure.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--out">
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-text">
                        Please also prepare the quotation for the Loop CRM project as well.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__datetime">
                3:15PM
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--in">
                  <div class="m-messenger__message-no-pic m--bg-fill-danger">
                    <span>
                      M
                    </span>
                  </div>
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-username">
                        Megan wrote
                      </div>
                      <div class="m-messenger__message-text">
                        Noted. I will prepare it.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--out">
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-text">
                        Thanks Megan. I will see you later.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="m-messenger__wrapper">
                <div class="m-messenger__message m-messenger__message--in">
                  <div class="m-messenger__message-pic">
                    <img src="{{ asset('assets/admin/img/user.jpg') }}"  alt=""/>
                  </div>
                  <div class="m-messenger__message-body">
                    <div class="m-messenger__message-arrow"></div>
                    <div class="m-messenger__message-content">
                      <div class="m-messenger__message-username">
                        Megan wrote
                      </div>
                      <div class="m-messenger__message-text">
                        Sure. See you in the meeting soon.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="m-messenger__seperator"></div>
            <div class="m-messenger__form">
              <div class="m-messenger__form-controls">
                <input type="text" name="" placeholder="Type here..." class="m-messenger__form-input">
              </div>
              <div class="m-messenger__form-tools">
                <a href="" class="m-messenger__form-attachment">
                  <i class="la la-paperclip"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane  m-scrollable" id="m_quick_sidebar_tabs_settings" role="tabpanel">
          <div class="m-list-settings">
            <div class="m-list-settings__group">
              <div class="m-list-settings__heading">
                General Settings
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Email Notifications
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" checked="checked" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Site Tracking
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  SMS Alerts
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Backup Storage
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Audit Logs
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" checked="checked" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
            </div>
            <div class="m-list-settings__group">
              <div class="m-list-settings__heading">
                System Settings
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  System Logs
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Error Reporting
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Applications Logs
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Backup Servers
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" checked="checked" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
              <div class="m-list-settings__item">
                <span class="m-list-settings__item-label">
                  Audit Logs
                </span>
                <span class="m-list-settings__item-control">
                  <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                    <label>
                      <input type="checkbox" name="">
                      <span></span>
                    </label>
                  </span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane  m-scrollable" id="m_quick_sidebar_tabs_logs" role="tabpanel">
          <div class="m-list-timeline">
            <div class="m-list-timeline__group">
              <div class="m-list-timeline__heading">
                System Logs
              </div>
              <div class="m-list-timeline__items">
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    12 new users registered
                    <span class="m-badge m-badge--warning m-badge--wide">
                      important
                    </span>
                  </a>
                  <span class="m-list-timeline__time">
                    Just now
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    System shutdown
                  </a>
                  <span class="m-list-timeline__time">
                    11 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                  <a href="" class="m-list-timeline__text">
                    New invoice received
                  </a>
                  <span class="m-list-timeline__time">
                    20 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                  <a href="" class="m-list-timeline__text">
                    Database overloaded 89%
                    <span class="m-badge m-badge--success m-badge--wide">
                      resolved
                    </span>
                  </a>
                  <span class="m-list-timeline__time">
                    1 hr
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    System error
                  </a>
                  <span class="m-list-timeline__time">
                    2 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server down
                    <span class="m-badge m-badge--danger m-badge--wide">
                      pending
                    </span>
                  </a>
                  <span class="m-list-timeline__time">
                    3 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server up
                  </a>
                  <span class="m-list-timeline__time">
                    5 hrs
                  </span>
                </div>
              </div>
            </div>
            <div class="m-list-timeline__group">
              <div class="m-list-timeline__heading">
                Applications Logs
              </div>
              <div class="m-list-timeline__items">
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    New order received
                    <span class="m-badge m-badge--info m-badge--wide">
                      urgent
                    </span>
                  </a>
                  <span class="m-list-timeline__time">
                    7 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    12 new users registered
                  </a>
                  <span class="m-list-timeline__time">
                    Just now
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    System shutdown
                  </a>
                  <span class="m-list-timeline__time">
                    11 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                  <a href="" class="m-list-timeline__text">
                    New invoices received
                  </a>
                  <span class="m-list-timeline__time">
                    20 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                  <a href="" class="m-list-timeline__text">
                    Database overloaded 89%
                  </a>
                  <span class="m-list-timeline__time">
                    1 hr
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    System error
                    <span class="m-badge m-badge--info m-badge--wide">
                      pending
                    </span>
                  </a>
                  <span class="m-list-timeline__time">
                    2 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server down
                  </a>
                  <span class="m-list-timeline__time">
                    3 hrs
                  </span>
                </div>
              </div>
            </div>
            <div class="m-list-timeline__group">
              <div class="m-list-timeline__heading">
                Server Logs
              </div>
              <div class="m-list-timeline__items">
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server up
                  </a>
                  <span class="m-list-timeline__time">
                    5 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    New order received
                  </a>
                  <span class="m-list-timeline__time">
                    7 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    12 new users registered
                  </a>
                  <span class="m-list-timeline__time">
                    Just now
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    System shutdown
                  </a>
                  <span class="m-list-timeline__time">
                    11 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                  <a href="" class="m-list-timeline__text">
                    New invoice received
                  </a>
                  <span class="m-list-timeline__time">
                    20 mins
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                  <a href="" class="m-list-timeline__text">
                    Database overloaded 89%
                  </a>
                  <span class="m-list-timeline__time">
                    1 hr
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    System error
                  </a>
                  <span class="m-list-timeline__time">
                    2 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server down
                  </a>
                  <span class="m-list-timeline__time">
                    3 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                  <a href="" class="m-list-timeline__text">
                    Production server up
                  </a>
                  <span class="m-list-timeline__time">
                    5 hrs
                  </span>
                </div>
                <div class="m-list-timeline__item">
                  <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                  <a href="" class="m-list-timeline__text">
                    New order received
                  </a>
                  <span class="m-list-timeline__time">
                    1117 hrs
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>-->
  <!-- end::Quick Sidebar -->         
    <!-- begin::Scroll Top -->
  <div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
  </div>
  <!-- end::Scroll Top -->

  <!--[if lt IE 9]>
      <script src="{{ url(elixir("assets/js/ie8.js")) }}"></script> 
  <![endif]-->
  <script src="{{ asset('assets/admin/scripts/vendor.js') }}"></script>
  <script src="{{ asset('assets/admin/scripts/master.js') }}"></script>
  <script src="{{ asset('assets/admin/scripts/admin.js') }}"></script>
  @include('master::scripts.date-js')
  @include('master::scripts.time-js')
  @include('master::scripts/filter-js')
  @include('master::scripts/table-js')
  @include('master::scripts/notifications-js')
  <script>
    $(window).on('load', function() {
        $('body').removeClass('m-page--loading');         
    });
  </script>
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