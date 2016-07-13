<div class="container">
  @if(count($items)>0)
  	<div class="row">
      <?php $project_id = 0; ?>
      <?php $section_id = 0; ?>
      @foreach($items as $item)
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
          <div class="row">
        @endif
        @include('singles.row-file')
      @endforeach
	</div>
    {!! $items->render() !!}
  @else
    <p>Actualmente no hay archivos en esta sección.</p>
  @endif
</div>