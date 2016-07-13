<script type="text/javascript"> 
  $(function () {
    $('#list-graph-<?php echo $graph_name; ?>').highcharts({
      chart: {
          type: 'column'
      },
      title: {
        text: 'Reporte en Barra: {{ trans("fields.".$column) }}'
      },
      xAxis: {
          type: 'category',
          labels: {
              rotation: -45,
              style: {
                  fontSize: '13px',
                  fontFamily: 'Verdana, sans-serif'
              }
          }
      },
      yAxis: {
          min: 0,
          title: {
              text: 'Número'
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: ''
      },
      series: [{
          name: 'Cantidad',
          data: [
            @foreach($graph_items as $item)
              [<?php echo '"'.trans('admin.'.$item->$column).' ('.$item->total.')"'; ?>,  {{ $item->total }}],
            @endforeach
          ],
          dataLabels: {
              enabled: true,
              rotation: -90,
              color: '#FFFFFF',
              align: 'right',
              x: 4,
              y: 10,
              style: {
                  fontSize: '13px',
                  fontFamily: 'Verdana, sans-serif',
                  textShadow: '0 0 3px black'
              }
          }
      }]
    });
  });
</script>