<div class="container fr-view">
    @if(count($items)>0)
        @foreach($items as $key => $item)
        	{!! $item->content !!}
        @endforeach
    @endif
</div>
