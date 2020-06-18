@if(isset($graphs)&&$graphs&&count($graphs)>0)	
  	<script src="https://code.highcharts.com/highcharts.js"></script>
  	<script src="http://code.highcharts.com/modules/exporting.js"></script>
  	@foreach($graphs as $graph_name => $graph)
  	  @if($graph["type"]=='lines')
		@include('master::scripts.graph-'.$graph["type"].'-js', ['graph_name'=>$graph_name, 'column'=>$graph["name"], 'label'=>$graph["label"], 'graph_items'=>$graph["subitems"]])
	  @else
		@include('master::scripts.graph-'.$graph["type"].'-js', ['graph_name'=>$graph_name, 'column'=>$graph["name"], 'label'=>$graph["label"], 'graph_items'=>$graph["subitems"]])
	  @endif
	@endforeach
@endif