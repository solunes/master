@if(count($items)>0)
  <div class="container-slider">
    <div id="layerslider" style="width:100%;height:400px;">
      @foreach($items as $item)

        <div class="ls-slide" data-ls="{{ $item->data_ls }}">
          <img class="ls-bg" src="{{ Asset::get_image_path('banner', 'normal', $item->image) }}">
          <div class="ls-l" style="top: 0px; left: 50%; font-size: 18px;">{!! Admin::make_section_buttons('banner', $item, $page->id, false) !!}</div>
        </div>
      @endforeach
    </div>
  </div>
@endif