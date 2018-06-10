@foreach($items as $item)
<li class="m-menu__item @if($item->active) m-menu__item--active  m-menu__item--active-tab @endif m-menu__item--submenu m-menu__item--tabs"  m-menu-submenu-toggle="tab" aria-haspopup="true">
  <a  href="{{ $item->url() }}" class="m-menu__link @if($item->hasChildren()) m-menu__toggle @endif ">
    <span class="m-menu__link-text">
      {!! $item->title !!}
    </span>
    <i class="m-menu__hor-arrow la la-angle-down"></i>
    <i class="m-menu__ver-arrow la la-angle-right"></i>
  </a>
  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
    @if($item->hasChildren())
    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
    <ul class="m-menu__subnav">
      @foreach($item->children() as $child)
      <li class="m-menu__item @if($child->active) m-menu__item--active @endif "  m-menu-link-redirect="1" aria-haspopup="true">
        <a  href="{{ $child->url() }}" class="m-menu__link @if($child->hasChildren()) m-menu__toggle @endif ">
          {!! $child->title !!}
        </a>
      </li>
      @endforeach
    </ul>
    @endif
  </div>
</li>
@endforeach

@if(Auth::check())
<li class="m-menu__item m-menu__item--submenu m-menu__item--tabs"  m-menu-submenu-toggle="tab" aria-haspopup="true">
  <a  href="{{ url('account') }}" class="m-menu__link m-menu__toggle ">
    <span class="m-menu__link-text">
      {{ trans('master::model.my_account') }}
    </span>
    <i class="m-menu__hor-arrow la la-angle-down"></i>
    <i class="m-menu__ver-arrow la la-angle-right"></i>
  </a>
  <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left m-menu__submenu--tabs">
    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
    <ul class="m-menu__subnav">
      <li class="m-menu__item "  m-menu-link-redirect="1" aria-haspopup="true">
        <a  href="{{ url('account') }}" class="m-menu__link ">
          <i class="m-menu__link-icon flaticon-support"></i>
          <span class="m-menu__link-text">
            {{ trans('master::model.my_profile') }}
          </span>
        </a>
      </li>
      <li class="m-menu__item "  m-menu-link-redirect="1" aria-haspopup="true">
        <a  href="{{ url('auth/logout') }}" class="m-menu__link ">
          <i class="m-menu__link-icon flaticon-support"></i>
          <span class="m-menu__link-text">
            {{ trans('master::model.logout') }}
          </span>
        </a>
      </li>
    </ul>
  </div>
</li>
@endif