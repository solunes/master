@extends('master::layouts/admin')

@section('content')
  <h1>Notificaciones</h1>
  @if(count($items)>0)
    <table class="table table-bordered table-hover dt-responsive">
      <thead>
        <tr class="title">
          <td>Nº</td>
          <td>Fecha</td>
          <td>Mensaje</td>
          <td>Link</td>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $key => $item)
          <tr @if(!$item->checked_date) class="unread" @endif>
            @if(!$item->checked_date)
              <?php $item->checked_date = date('Y-m-d H:i:s');
              $item->save(); ?>
            @endif
            <td>{{ $key+1 }}</td>
            <td><a class="help" data-livestamp="{{ $item->created_at->timestamp }}" title="{{ $item->created_at }}"></a></td>
            <td>{{ $item->notification_messages->where('type', 'dashboard')->first()->message }}</td>
            <td>@if($item->url) <a href="{{ $item->url }}" target="_blank">Abrir URL</a> @else - @endif </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {!! $items->appends(request()->except(array('page')))->render() !!}
  @endif
@endsection
@section('script')
  @include('master::scripts.tooltip-js')
@endsection