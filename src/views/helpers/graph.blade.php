 @if(isset($graphs)&&$graphs&&count($graphs)>0)
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="http://code.highcharts.com/modules/exporting.js"></script>
  @foreach($graphs as $graph_name => $graph)
	@include('scripts.graph-'.$graph["type"].'-js', ['graph_name'=>$graph_name, 'column'=>$graph["name"], 'graph_items'=>$graph["items"], 'graph_subitems'=>$graph["subitems"]])
  @endforeach
@endif