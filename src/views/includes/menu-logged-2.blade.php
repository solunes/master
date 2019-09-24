@foreach($items as $item)
<li class="nav-item @if($item->active) active  @endif ">
  <a  href="{{ $item->url() }}" @if($item->hasChildren()) @endif ">
    <i class="feather icon-check-square"></i>
    <span class="menu-title">
      {!! $item->title !!}
    </span>
  </a>
  @if($item->hasChildren())
    <ul class="menu-content">
      @foreach($item->children() as $child)
      <li @if($child->active) @endif >
        <a  href="{{ $child->url() }}" @if($child->hasChildren()) @endif>
          <i class="feather icon-circle"></i><span class="menu-item" data-i18n="{!! $child->title !!}">{!! $child->title !!}</span>
        </a>
      </li>
      @endforeach
    </ul>
  @endif
</li>
@endforeach

@if(Auth::check()&&config('solunes.admin_initial_menu.my_account'))
<li class="nav-item">
  <a href="#">
    <i class="feather icon-check-square"></i>
    <span class="menu-title">
      {{ trans('master::model.my_account') }}
    </span>
  </a>
  <ul class="menu-content">
    @if(config('solunes.admin_initial_menu.my_profile'))
    <li>
      <a href="{{ url('account/my-account/1354351278') }}">
        <i class="feather icon-circle"></i><span class="menu-item" data-i18n="{{ trans('master::model.my_profile') }}">{{ trans('master::model.my_profile') }}</span>
      </a>
    </li>
    @endif
    @if(config('solunes.admin_initial_menu.logout'))
    <li>
      <a href="{{ url('auth/logout') }}">
        <i class="feather icon-circle"></i><span class="menu-item" data-i18n="{{ trans('master::model.logout') }}">{{ trans('master::model.logout') }}</span>
      </a>
    </li>
    @endif
  </ul>
</li>
@endif