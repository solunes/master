<div class="container">
  @if(count($items)>0)
    <div id="map-canvas-1" style="height: 400px;"></div>
  @endif
</div>
@include('scripts.location-js', ['id'=>1, 'project'=>false])