<div class="container">
    @if(count($items)>0)
        @foreach($items as $key => $item)
        	@include('helpers.title', ['title'=>$item->name])
        @endforeach
    @endif
</div>