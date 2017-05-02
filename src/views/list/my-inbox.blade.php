@extends('master::layouts/admin')

@section('content')
<h1 class="page-title"> Mi Bandeja de Entrada </h1>

<div class="inbox">
  <div class="inbox-conversations">
    <a href="{{ url('admin/create-inbox') }}" class="btn btn-site"> Crear Conversación Nueva </a>
    @foreach($items as $key => $item)
      <a href="{{ url('admin/inbox/'.$item->id) }}">
        <div class="inbox-conversation @if(!$item->me->checked) unread @endif ">
          <img class="pull-left" src="{{ asset('assets/admin/img/no_picture.jpg') }}" width="45px" height="45px">
          <div class="inbox-conversation-users">
            @foreach($item->other_users->take(2) as $subkey => $user)
              @if($subkey>0)
                , 
              @endif
              {{ $user->user->name }}
            @endforeach
            @if(count($item->other_users)>2)
              ...
            @endif
          </div>
          <div class="inbox-conversation-message">
            {{ $item->last_message->message }}
          </div>
          <div class="inbox-conversation-date">
            <span class="help" data-livestamp="{{ $item->updated_at->timestamp }}" title="{{ $item->updated_at }}"></span>
          </div>
        </div>
      </a>
    @endforeach
    {!! $items->appends(request()->except(array('page')))->render() !!}
  </div>
</div>
@endsection
@section('script')
  @include('master::scripts.tooltip-js')
@endsection