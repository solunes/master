@if(isset($notifications)&&count($notifications)>0)
  <h3>Notificaciones</h3>
  <ul>
	  @foreach($notifications as $notification)
      <li><strong>{{ $notification->user->name.' | '.$notification->created_at->format('Y-m-d H:i') }}</strong>
      {!! $notification->message !!}</li>
	  @endforeach
  </ul>
@endif