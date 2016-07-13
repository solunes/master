<div class="container">
  @if(count($items)>0)
    @foreach($items as $item)
      @include('singles.row-file')
    @endforeach
    {!! $items->render() !!}
  @else
    <p>Actualmente no hay archivos en esta sección.</p>
  @endif
</div>