<div class="container">
  @if(count($items)>0)
    <div class="row grid">
      @foreach($items as $key => $item)
        <div class="col-sm-4 col-xs-6 grid-sizer"></div>
        @include('singles.job')
      @endforeach
    </div>
    {!! $items->render() !!}
  @else
    <p>Actualmente no hay convocatorias en esta sección.</p>
  @endif
</div>