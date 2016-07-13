<p class="dnl-nav-title">{{ trans('main.dashboard') }}</p>
<ul class="dnl-nav">
  <li><a href="{{ url('') }}"><span class="dnl-link-icon"><i class="fa fa-rotate-left"></i></span> Volver a Sitio</a></li>
  @foreach($items as $item)
    <li {!! $item->attributes() !!}>
      {!! Func::menu_link($item, 1) !!}
      @if($item->hasChildren())
        <ul class="dnl-sub-one collapse" id="{{ $item->id }}Dropdown">
          @foreach($item->children() as $child)
            @if($child->hasChildren())
            <li class="dropdown-right-onhover">
            @else
            <li>
            @endif
              {!! Func::menu_link($child, 2) !!}
              @if($child->hasChildren())
                <ul class="dropdown-menu">
                  @foreach($child->children() as $child2)
                    <li>{!! Func::menu_link($child2, 3) !!}</li>
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
</ul>
@if(Auth::check())
  <p class="dnl-nav-title">{{ trans('main.account') }}</p>
  <ul class="dnl-nav">
    <li><a href="{{ url('account') }}"><span class="dnl-link-icon"><i class="fa fa-user"></i></span>
    <span class="dnl-link-text">{{ trans('main.profile') }}</span></a></li>
    <li><a href="{{ url('auth/logout') }}"><span class="dnl-link-icon"><i class="fa fa-sign-out"></i></span>
    <span class="dnl-link-text">{{ trans('main.logout') }}</span></a></li>
  </ul>
@endif