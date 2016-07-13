<script type="text/javascript"> 
  $(function () {
      $('#list-graph-<?php echo $graph_name; ?>').highcharts({
          title: {
            text: 'Reporte en Barra: {{ trans("fields.".$column) }}',
              x: -20 //center
          },
          xAxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
          },
          yAxis: {
              title: {
                  text: 'Resultados'
              },
              plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
              }]
          },
          legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
          series: [
            @foreach($graph_items as $item)
              {name: <?php echo '"'.trans('admin.'.$item->$column).' ('.$item->total.')."'; ?>, data: {{ $graph_subitems[$item->$column] }}},
            @endforeach
          ]
      });
  });
</script>