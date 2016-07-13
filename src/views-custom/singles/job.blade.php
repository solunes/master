<div class="col-sm-4 col-xs-6 grid-item">
  <div class="job center">
      <a target="_blank" href="{{ url('convocatoria/'.$item->id) }}"><div class="title">
        <h4>{{ $item->name }}</h4>
      </div></a>
    <div class="content">
      {!! $item->content !!}
      <p><a href="{{ Asset::get_file($item->file, 'tdr') }}">Descargar TDR</a></p>
    </div>
  </div>
</div>