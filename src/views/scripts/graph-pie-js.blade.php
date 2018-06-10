<script type="text/javascript"> 
  $(function () {
    $('#list-graph-<?php echo $graph_name; ?>').highcharts({
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: 1,//null,
          plotShadow: false
      },
      title: {
        text: '{{ trans("master::admin.graph_label_pie")." ".$label }}'
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                  style: {
                      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                  }
              }
          }
      },
      series: [{
          type: 'pie',
          name: '{{ trans("master::admin.graph_total") }}',
          data: [
          @foreach($graph_items as $item)
            [<?php echo '"'.$graph_field_names[$item->$column].' ('.$item->total.')"'; ?>,  {{ $item->total }}],
          @endforeach
          ]
      }]
    });
  });
</script>