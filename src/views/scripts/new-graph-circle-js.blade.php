<script type="text/javascript">
  Highcharts.chart('graph-container', {
    chart: {
        height: 500,
        type: 'pie'
    },
    title: {
        text: '<?php echo $node->plural; ?>'
    },
    
    accessibility: {
        announceNewData: {
            enabled: true
        },
        point: {
            valueSuffix: '%'
        }
    },

    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [
        {
            name: "Años",
            colorByPoint: true,
            data: [
              @foreach($real_years as $year_id => $year_name)
                {
                    name: "<?php echo $year_name.' - Total: '.$totals_data[$year_id]; ?>",
                    y: <?php echo round(($totals_data[$year_id]/$total_data)*100,2); ?>,
                    drilldown: "<?php echo $year_id; ?>"
                },
              @endforeach
            ]
        }
    ],
    drilldown: {
        series: [
          @foreach($real_years as $year_id => $year_name)
            {
                name: "<?php echo $year_name.' - Total: '.$totals_data[$year_id]; ?>",
                id: "<?php echo $year_id; ?>",
                data: [
                  @foreach($main_cols_complete as $col_name => $col_value)
                    ["<?php echo $col_name.' - Total: '.$col_value[$year_id]; ?>", <?php echo round(($col_value[$year_id]/$totals_data[$year_id])*100,2); ?>],
                  @endforeach
                ]
            },
          @endforeach
        ]
    },

    responsive: {
        rules: [{
            condition: {
                maxWidth: 400
            },
            chartOptions: {
                series: [{
                }, {
                    id: 'versions',
                    dataLabels: {
                        enabled: false
                    }
                }]
            }
        }]
    }

});
</script>