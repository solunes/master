<div class="col-sm-4">
  <div class="file">
    <div class="image">
      {!! Asset::get_image('portrait', 'normal', $item->image) !!}
    </div>
    <div class="row">
      <div class="col-sm-6 date">
        <h4>{{ $item->size_ext }}</br><span>{{ $item->size }}</span></h4>
      </div>
      <a target="_blank" href="{{ Asset::get_file($item->file, 'files') }}">
        <div class="col-sm-6 button">
          <i class="fa fa-arrow-down"></i>
        </div>
      </a>
    </div>
  </div>
</div>