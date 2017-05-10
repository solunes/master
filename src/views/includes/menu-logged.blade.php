<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
  <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
  <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
  <li class="sidebar-toggler-wrapper hide">
      <div class="sidebar-toggler">
        <span></span>
      </div>
  </li>
  <!-- END SIDEBAR TOGGLER BUTTON -->
  <li class="nav-item start active open">
      <a href="{{ url('admin') }}" class="nav-link nav-toggle">
        <i class="icon-home"></i>
        <span class="title">{{ trans('master::model.my_dashboard') }}</span>
        <span class="selected"></span>
      </a>
  </li>
  <li class="heading">
      <h3 class="uppercase">Menu</h3>
  </li>
  @foreach($items as $item)
    <li class="nav-item">
      {!! Func::menu_link($item, 1) !!}
      @if($item->hasChildren())
        <ul class="sub-menu">
          @foreach($item->children() as $child)
            <li class="nav-item">
              {!! Func::menu_link($child, 2) !!}
              @if($child->hasChildren())
                <ul class="sub-menu">
                  @foreach($child->children() as $child2)
                    <li class="nav-item">{!! Func::menu_link($child2, 3) !!}</li>
                  @endforeach
                </ul>
              @endif
            </li>
          @endforeach
        </ul>
      @endif
    </li>
    @if($item->divider)
      <li{{\HTML::attributes($item->divider)}}></li>
    @endif
  @endforeach
  @if(Auth::check())
    <li class="heading">
      <h3 class="uppercase">{{ trans('master::model.my_account') }}</h3>
    </li>
    <li class="nav-item"><a class="nav-link" href="{{ url('account') }}">
      <i class="fa fa-user"></i><span class="title">{{ trans('master::model.my_profile') }}</span>
    </a></li>
    <li class="nav-item"><a class="nav-link" href="{{ url('auth/logout') }}">
      <i class="fa fa-sign-out"></i><span class="title">{{ trans('master::model.logout') }}</span>
    </a></li>
  @endif
</ul>