<script type="text/javascript">
    var owl = $('#owl-projects');
    var markers = [];
    var markerGroups = {
      @foreach($items as $key => $project)
        "project-{{ $key }}": [],
      @endforeach
    };
    function initialize() {

        var bounds = new google.maps.LatLngBounds();

        var styles = @include('scripts.map-style-js');
        var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
        var mapOptions = {
          center: new google.maps.LatLng(-16.51409139, -68.12485814),
          zoom: 13,
          minZoom: 5,
          maxZoom: 17,
          scrollwheel: false,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          mapTypeControl: false,
          streetViewControl: false,
          zoomControl: true,
          zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
          }
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

        var locations = [
          @foreach ($items as $key => $project)
            @if(count($project->locations)>0)
              @foreach ($project->locations as $location)
                ['<h3>{{ $location->region }}</h3><h4>{{ $location->name }}</h4>', {{ $location->latitude }}, {{ $location->longitude }}, '{{ $location->marker }}', {{ $key }}],
              @endforeach
            @endif
          @endforeach
        ];

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        for (i = 0; i < locations.length; i++) {  
          marker = new google.maps.Marker({
            id: i,
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: locations[i][3],
            animation: google.maps.Animation.DROP
          });

          bounds.extend(marker.position);

          google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
            return function() {
              infowindow.setContent(locations[i][0]);
              infowindow.open(map, marker);
              owl.trigger('to.owl.carousel', locations[i][4]);  // Go to x slide
            }
          })(marker, i));

          markerGroups['project-'+locations[i][4]].push(marker);
          markers.push(marker);

        }  
        map.fitBounds(bounds);
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');
        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('legend')); 
    }

    function doSetTimeout(marker) {
      setTimeout(function(){ 
        marker.setAnimation(null); 
      }, 1500);
    }

    function toggleGroup(project_key) {
        for (var i = 0; i < markerGroups[project_key].length; i++) {
          var marker = markerGroups[project_key][i];
          marker.setAnimation(google.maps.Animation.BOUNCE);
          doSetTimeout(marker);
        }
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>