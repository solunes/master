<div class="container">
  @if(count($items)>0)
    <div class="row">
      @foreach($items as $item)

        <div class="col-sm-4">
          <a href="#" class="comunicate">
            {!! Asset::get_image('comunicate', 'normal', $item->image, 'img-responsive') !!}
              <div class="comunicate_content bottom-to-top">
                <h3>{{ $item->name }}</h3>
                <p>{{ $item->content }}</p>
              </div>
          </a>
        </div>

      @endforeach
    </div>
  @else
    <p>Actualmente no hay comunicados en esta sección.</p>
  @endif
</div>