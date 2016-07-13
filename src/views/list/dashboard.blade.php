@extends('layouts/master')

@section('content')
	<br><h1>Bienvenido al Dashboard!</h1>
	<div class="row">
		<div class="col-sm-6">
			<h3>Últimas Actividades en el Sitio</h3>
			<ul>
				@foreach($activities as $activity)
					<li>
						@if($activity->action!='node_deleted')
							<a target="_blank" href="{{ url('admin/model/'.$activity->node->name.'/edit/'.$activity->item_id) }}">
						@endif
							<strong>{{ trans_choice('model.'.$activity->node->name, 1) }}</strong> 
						@if($activity->action!='node_deleted')
							</a>
						@endif
						 | 
						{{ trans('admin.'.$activity->action).' '.trans('admin.by').' '.$activity->username }}
						{{ ' ('.$activity->created_at->format('Y-m-d H:i').')' }}
					</li>
				@endforeach
			</ul>
		</div>
		<div class="col-sm-6">
			<h3>Últimas Notificaciones</h3>
			<ul>
				@foreach($notifications as $notification)
					<li>{{ $notification->created_at->format('Y-m-d H:i').' | '.$notification->message }}</li>
				@endforeach
			</ul>
		</div>
	</div>
@endsection