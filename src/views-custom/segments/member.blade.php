<div class="container">
  @if(count($items)>0)
    <div class="row">
      @foreach($items as $item)
        @if($item->type=='member')
          <div class="col-sm-4">
            <div class="member wow zoomIn"><div class="card">
              <div class="face front">
                {!! Asset::get_image('member-logo', 'normal', $item->logo) !!}
              </div>
              <a href="{{ url('miembro/'.$item->slug) }}">
                <div class="face back center" style="background-color: {{ $item->color }}">
                  <h3>{{ $item->short_name }}</h3>
                  <p>{{ $item->contact_email }}<br>
                  {{ $item->contact_phone }}</p>
                  <p class="link"><i class="fa fa-arrow-circle-o-right"></i> Ver miembro</p>
                </div>
              </a>
            </div></div>
          </div>
        @endif
      @endforeach
    </div>
  @else
    <p>Actualmente no hay proyectos en esta sección.</p>
  @endif
</div>