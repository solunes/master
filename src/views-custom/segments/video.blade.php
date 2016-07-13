<div class="container">
  @if(count($items)>0)
    <div class="row grid">
      <div class="col-sm-4 col-xs-6 grid-sizer"></div>
      <?php $project_id = 0; ?>
      <?php $section_id = 0; ?>
      @foreach($items as $key => $item)
        @if($item->project_id!=$project_id)
        <?php $project_id = $item->project_id; ?>
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
        @include('singles.video', ['model'=>'video', 'name'=>$item->name, 'thumb'=>'http://img.youtube.com/vi/'.$item->code.'/0.jpg', 'full'=>'http://www.youtube.com/watch?v='.$item->code])
      @endforeach
    </div>
    {!! $items->render() !!}
  @else
    <p>Actualmente no hay videos en esta sección.</p>
  @endif
</div>