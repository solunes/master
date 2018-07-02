@if(count($items)>0)
  	<script src="https://code.highcharts.com/highcharts.js"></script>
  	<script src="http://code.highcharts.com/modules/exporting.js"></script>
	@include('master::scripts.graph-'.$graph["type"].'-js', ['graph_name'=>$graph_name, 'column'=>$graph["name"], 'label'=>$graph["label"], 'graph_items'=>$graph["items"], 'graph_subitems'=>$graph["subitems"], 'graph_field_names'=>$graph["field_names"]])
@endif