@if(isset($activities)&&count($activities)>0)
  <h3>Actualizaciones</h3>
  <div data-accordion-group>
  	@foreach($activities as $activity)
  	  <div class="accordion" data-accordion>
        <div data-control>
      	  <h4><i class="fa fa-caret-down"></i> 
      	  	{{ trans('admin.'.$activity->action).' '.trans('admin.by').' '.$activity->username }}
            {{ ' ('.$activity->created_at->format('Y-m-d H:i').')' }}
      	  </h4>
        </div>
        <div data-content>
          <div class="accordion-content">
            {!! $activity->message !!}
          </div>
        </div>
  	@endforeach
  </div>
@endif