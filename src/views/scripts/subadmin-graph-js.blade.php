@if(config('solunes.customer_dashboard_graphs.'.$model))
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
  Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: "{{ config('solunes.customer_dashboard_graphs.'.$model)['title'] }}"
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
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [
        @foreach($graph_items as $subitem_label => $subitem_total)
          {name: <?php echo '"'.$subitem_label.'"'; ?>, data: <?php echo $subitem_total; ?> },
        @endforeach
    ]
});
</script>
@endif