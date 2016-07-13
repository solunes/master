<div class="container">
  @include('helpers.filter', ['node'=>$node['node']])
</div>
<div class="row no-gutters">
  <div class="col-sm-5">
    <div height="500" id="map-canvas"></div>
  </div>
  <div class="col-sm-7">
    @if(count($items)>0)
      <div id="owl-projects" class="owl-carousel">
        @foreach($items as $key => $item)
          <div class="item" data-count="{{ $key }}">
            <h3 class="main" style="color: {{ $item->member->color }}"><img src="{{ Asset::get_image_path('member-isotype', 'normal', $item->member->isotype) }}">{{ $item->member->short_name }}</h3>
            <p>{{ ($item->name) }}</p>
            <p><span style="color: {{ $item->member->color }}">Área:</span> 
              @foreach($item->sectors as $sector)
                {{ $sector->name }}
              @endforeach
            </p>
            @if(count($item->locations)>0)
              <?php $region = null; ?>
              <p>
              @foreach($item->locations as $location)
                @if($location->region!=$region)
                  @if($region)
                    <br>
                  @endif
                  <?php $region = $location->region; ?>
                  <?php $location_count = 0; ?>
                  <span style="color: {{ $item->member->color }}">{{ $location->region.': ' }}</span>
                @endif
                @if($location_count>0)
                  / 
                @endif
                {{ $location->name }}
                <?php $location_count++; ?>
              @endforeach
              </p>
            @endif
            @if(count($item->files)>0)
              <h1>Archivos</h1>
              <div class="content-file">
                <div class="row">
                  @foreach($item->files as $sub)
                    @include('singles.row-file', ['item'=>$sub])
                  @endforeach
                </div>
              </div>
            @endif
            @if(count($item->photos)>0)
              <h1>Imágenes</h1>
              <div class="content-photo">
                <div class="row">
                  @foreach($item->photos as $sub)
                    @include('singles.photo', ['model'=>'photo', 'name'=>$sub->name, 'thumb'=>Asset::get_image_path('photo', 'thumb', $sub->image), 'full'=>Asset::get_image_path('photo', 'normal', $sub->image), 'counter'=>1])
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <p>Actualmente no hay proyectos en esta sección.</p>
    @endif
  </div>
</div>
