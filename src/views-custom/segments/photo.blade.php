<div class="container">
  @if(count($items)>0)
    <div class="row grid">
      <div class="col-sm-4 col-xs-6 grid-sizer"></div>
      <?php $project_id = 0; ?>
      <?php $section_id = 0; ?>
      @foreach($items as $key => $item)
        @if($item->project_id!=$project_id)
        <?php $project_id = $item->project_id;
        $counter = 0; ?>
          </div>
          @if($item->project)
            @if($item->project->section_id!=$section_id)
              <?php $section_id = $item->project->section_id; ?>
              <h1>{{ $item->project->section->pages()->first()->name }}</h1>
            @endif
            <h2>{{ $item->project->name }}</h2>
          @else
            <h2>Otros</h2>
          @endif
          <div class="row grid">
          <div class="col-sm-4 col-xs-6 grid-sizer"></div>
        @endif
        @include('singles.photo', ['model'=>'photo', 'name'=>$item->name, 'thumb'=>Asset::get_image_path('photo', 'thumb', $item->image), 'full'=>Asset::get_image_path('photo', 'normal', $item->image), 'counter'=>$counter++])
      @endforeach
    </div>
  @else
    <p>Actualmente no hay fotos en esta sección.</p>
  @endif
</div>