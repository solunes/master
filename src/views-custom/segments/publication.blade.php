<div class="container">
  @if(count($items)>0)
    <?php $member_id = 0; ?>
    <div class="row grid">
      @foreach($items as $key => $item)
        @if($member_id!=$item->member_id)
          <?php $member_id = $item->member_id; ?>
          </div>
          <h2 class="member-title"><img src="{{ Asset::get_image_path('member-isotype', 'normal', $item->member->isotype) }}">{{ $item->member->short_name }}</h2>
          <div class="row grid">
        @endif
        <a target="_blank" href="{{ Asset::get_file('publication-file', $item->file) }}"><div class="col-sm-4 col-xs-6 grid-item">
          <div class="new_img">{!! Asset::get_image('publication-image', 'thumb', $item->image) !!}</div>
          <div class="box-info center" style="color: {{ $item->member->color }}">
            <p class="subtitle">{{ $item->name }}</p>
            <p class="subtext">Publicado el {{ $item->created_at->format('Y-m-d') }}</p>
          </div>
        </div></a>
      @endforeach
    </div>
    {!! $items->render() !!}
  @else
    <p>Actualmente no hay artículos en esta sección.</p>
  @endif
</div>