@extends('master::layouts/admin')
@section('title', 'Instrucciones')

@section('content')
  <div class="main-content main-content-2">
    <div class="login">
      <div class="container fr-view">
          @if(count($items)>0)
              @foreach($items as $key => $item)
                {!! $item->content !!}
              @endforeach
          @endif
      </div>
    </div>
  </div>
@endsection