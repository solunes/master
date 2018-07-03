@extends('master::layouts/admin')

@section('content')
    <div class="m-subheader ">
      <div class="d-flex align-items-center">
        <div class="mr-auto">
          <h3 class="m-subheader__title ">
            Dashboard
          </h3>
        </div>
        <div>
          <span class="m-subheader__daterange">
            <span class="m-subheader__daterange-label">
              <span class="m-subheader__daterange-title">Tipo de Gráfico: Barra</span>
            </span>
            <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
              <i class="la la-angle-down"></i>
            </a>
          </span>
        </div>
        <div>
          <span class="m-subheader__daterange" id="reportrange">
            <span class="m-subheader__daterange-label">
              <span class="m-subheader__daterange-title"></span>
              <span class="m-subheader_reportrange_daterange-date m--font-brand"></span>
            </span>
            <a href="#" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
              <i class="la la-angle-down"></i>
            </a>
          </span>
        </div>
      </div>
    </div>

    <h4>
      @if(count($indicators)>0)
        @foreach($indicators as $key => $indicator)
          @if($key>0) | @endif
          <a href="{{ url('admin?indicator_id='.$indicator->id) }}">{{ $indicator->name }}</a>
        @endforeach
      @endif
    </h4>
   
    @if($indicator&&count($items)>0)
      <div class="row">
        <div class="col-md-7 col-sm-8">
          <div id="list-graph-indicator-{{ $indicator->id }}" style="width: 100%; height: 400px;"></div>
        </div>
        <div class="col-md-5 col-sm-4">
          FILTROS
          <br>{{ count($items) }} items.
        </div>
      </div>
    @else
      <div class="row">
        <div class="col-sm-12">
          <h3>No se encontraron resultados para su gráfico.</h3>
        </div>
      </div>
    @endif
@endsection

@section('script')
  <script src="{{ asset('assets/admin/scripts/dashboard.js') }}"></script>
  <script type="text/javascript">
  $(function() {

    //var locale = window.navigator.userLanguage || window.navigator.language;
    var start = moment('{{ $start_date }}');
    var end = moment('{{ $end_date }}');
    var max = moment();

    function cb(start, end)  {
      $('#reportrange span').html(start.locale('es').format('D [de] MMMM, YYYY') + ' - ' + end.locale('es').format('D [de] MMMM, YYYY'));
      if($('#reportrange').hasClass('loaded')){
        var url = "{{ url('admin') }}?asd=1&start_date="+start.format('YYYY-MM-DD')+"&end_date="+end.format('YYYY-MM-DD');
        $( location ).attr("href", url);
      } else {
        $('#reportrange').addClass('loaded');
      }

    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: max,
        locale: {
          customRangeLabel: "Rango personalizado",
          applyLabel: "Aplicar",
          cancelLabel: "Cancelar",
          daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
          monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
          firstDay: 1
        },
        ranges: {
           'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
           'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
           'Este mes': [moment().startOf('month'), moment().endOf('month')],
           'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
  });
  </script>

  <script type="text/javascript"> 
    function e(e,t,a,i){
      $('<div id="tooltip" class="chart-tooltip">'+i+"</div>").css({position:"absolute",display:"none",top:t-40,left:e-40,border:"0px solid #ccc",padding:"2px 6px","background-color":"#fff"}).appendTo("body").fadeIn(200);
    };
  </script>

  @include('master::helpers.graph')
@endsection