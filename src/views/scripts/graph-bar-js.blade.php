<script type="text/javascript"> 
  $(function () {
    $('#list-graph-<?php echo $graph_name; ?>').highcharts({
      chart: {
          type: 'column'
      },
      title: {
        text: '{{ trans("master::admin.graph_label_bar")." ".$label }}'
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
              text: '{{ trans("master::admin.graph_number") }}'
          }
      },
      legend: {
          enabled: false
      },
      tooltip: {
          pointFormat: ''
      },
      series: [{
          name: '{{ trans("master::admin.graph_quantity") }}',
          data: [
            @foreach($graph_items as $item)
            [<?php echo '"'.$graph_field_names[$item->$column].' ('.$item->total.')"'; ?>,  {{ $item->total }}],
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