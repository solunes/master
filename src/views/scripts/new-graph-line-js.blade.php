<script type="text/javascript">
  Highcharts.chart('graph-container', {
    chart: {
        height: 600
    },
    title: {
        text: '<?php echo $node->plural; ?>'
    },
    yAxis: {
        title: {
            text: ''
        }
    },

    xAxis: {
      categories: [
        @foreach($real_years as $year_id => $year_name)
          '{{ $year_name }}',
        @endforeach
      ]
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
        }
    },

    series: [
      @foreach($main_cols_complete as $col_name => $col_value)
      {
        name: '<?php echo $col_name; ?>',
        data: [
          @foreach($real_years as $year_id => $year_name)
          <?php echo $col_value[$year_id]; ?>,
          @endforeach
        ]
      },
      @endforeach
    ],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
</script>