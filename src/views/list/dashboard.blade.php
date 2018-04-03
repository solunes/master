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

    <h4><a href="{{ url('admin/model/indicator/create') }}">Crear indicador</a> | 
      <a href="{{ url('admin/indicators') }}">Agregar/Ocultar indicador</a></h4>

    <!-- BEGIN DASHBOARD STATS 1-->
    <div class="row">
      @foreach($block_alerts as $alert)
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
          <a class="dashboard-stat dashboard-stat-v2 {{ $alert->indicator->color }}" href="#">
            <div class="visual"><i class="fa fa-comments"></i></div>
            <div class="details">
              @if(config('solunes.get_indicator_result')&&$custom_results = \CustomFunc::get_indicator_result('number', $alert->indicator->result_custom, $alert, $start_date, $end_date))
                {!! $custom_results !!}
              @else
                <div class="number">
                  @if($subalert = $alert->indicator->indicator_values()->where('date', '<=', $end_date)->orderBy('date','DESC')->first())
                    <span data-counter="counterup" data-value="{{ $subalert->value }}">{{ $subalert->value }}</span>
                  @else
                    <span data-counter="counterup" data-value="0">0</span>
                  @endif
                </div>
                <div class="desc"> {{ $alert->indicator->name }}
                  @if($alert->goal)
                    <br>Meta: {{ $alert->goal }}
                  @endif
                </div>
              @endif
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <div class="clearfix"></div>
    <!-- END DASHBOARD STATS 1-->
    
    <div class="row">
      @foreach($graph_alerts as $alert)
        <div class="col-md-6 col-sm-6">
            <!-- BEGIN PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-dark bold uppercase">{{ $alert->indicator->name }} - 
                        <a href="{{ url('admin/model/indicator/edit/'.$alert->parent_id.'/es') }}"><i class="fa fa-pencil"></i> Editar</a></span>
                    </div>
                    <div class="tools">
                        
                        <a href="" class="collapse"> </a>
                        <a href="" class="reload"> </a>
                        <a href="" class="remove"> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div id="plot_example_{{ $alert->id }}" class="chart"> </div>
                </div>
            </div>
            <!-- END PORTLET-->
        </div>
      @endforeach
    </div>
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
    @foreach($graph_alerts as $alert)
      <?php $graph_values = $alert->indicator->indicator_values()->where('type', 'normal')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('date','DESC')->get();
        if(count($graph_values)>10){
          $every = count($graph_values)/10;
          if($every<1.5){
            $every = count($graph_values)/4;
          }
          $graph_values = $graph_values->every(round($every));
        }
        $graph_values = $graph_values->lists('value','date')->reverse();
        ?>
      var t=[
        @foreach($graph_values as $date => $value)
          ["{{ substr($date,5) }}", {{ $value }}],
        @endforeach
      ];
      @if(config('solunes.get_indicator_result')&&$custom_results = \CustomFunc::get_indicator_result('line', $alert->indicator->result_custom, $alert, $start_date, $end_date))
        {!! $custom_results['data'] !!}
      @endif
      @if($alert->goal)
      var g=[
        @foreach($graph_values as $date => $value)
          ["{{ substr($date,5) }}", {{ $alert->goal }}],
        @endforeach
      ];
      @endif
      var a=($.plot(
        $("#plot_example_{{ $alert->id }}"),
        [
          {data:t,lines:{fill:.6,lineWidth:0},color:["{{ $alert->indicator->color }}"]},
          @if(isset($custom_results)&&$custom_results)
            {!! $custom_results['line'] !!}
            {!! $custom_results['point'] !!}
          @endif
          @if($alert->goal)
          {data:g,lines:{fill:0,lineWidth:2},color:["green"]},
          @endif
          {data:t,points:{show:!0,fill:!0,radius:5,fillColor:"{{ $alert->indicator->color }}",lineWidth:3},color:"#fff",shadowSize:0}
        ],
        {xaxis:{tickLength:0,tickDecimals:0,mode:"categories",font:{
            lineHeight:14,style:"normal",variant:"small-caps",color:"{{ $alert->indicator->color }}"}
        },
        yaxis:{ticks:5,tickDecimals:0,tickColor:"#eee",font:{lineHeight:14,style:"normal",variant:"small-caps",color:"{{ $alert->indicator->color }}"}},
        grid:{hoverable:!0,clickable:!0,tickColor:"#eee",borderColor:"#eee",borderWidth:1}}
      ),null);
      $("#plot_example_{{ $alert->id }}").bind("plothover",function(t,i,l){
        if($("#x").text(i.x.toFixed(2)),$("#y").text(i.y.toFixed(2)),l){
          if(a!=l.dataIndex){
            a=l.dataIndex,
            $("#tooltip").remove();
            l.datapoint[0].toFixed(2),l.datapoint[1].toFixed(2);
            e(l.pageX,l.pageY,l.datapoint[0],"{{ $alert->indicator->name }}: "+l.datapoint[1])
          }
        } else $("#tooltip").remove(),a=null
      });
    @endforeach
  </script>
@endsection