<script type="text/javascript"> 
  $(function () {
      $('#list-graph-<?php echo $graph_name; ?>').highcharts({
          title: {
            text: '{{ trans("master::admin.graph_label_line")." ".$label }}',
              x: -20 //center
          },
          xAxis: {
              categories: [
                "{{ trans('master::admin.jan') }}",
                "{{ trans('master::admin.feb') }}",
                "{{ trans('master::admin.mar') }}",
                "{{ trans('master::admin.apr') }}",
                "{{ trans('master::admin.may') }}",
                "{{ trans('master::admin.jun') }}",
                "{{ trans('master::admin.jul') }}",
                "{{ trans('master::admin.aug') }}",
                "{{ trans('master::admin.sep') }}",
                "{{ trans('master::admin.oct') }}",
                "{{ trans('master::admin.nov') }}",
                "{{ trans('master::admin.dec') }}"
              ]
          },
          yAxis: {
              title: {
                  text: '{{ trans("master::admin.graph_results") }}'
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
            @foreach($graph_items as $subitem_label => $subitem_total)
              {name: <?php echo '"'.$field_names[$subitem_label].'"'; ?>, data: <?php echo $subitem_total; ?> },
            @endforeach
          ]
      });
  });
</script>