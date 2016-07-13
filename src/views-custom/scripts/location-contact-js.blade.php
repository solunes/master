<script type="text/javascript">
    var markers = [];
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
          @foreach ($items as $key => $item)
            ['<h3>{{ $item->name }}</h3><h4>{{ $item->address }}</h4>', {{ $item->latitude }}, {{ $item->longitude }}, '{{ $item->marker }}', {{ $key }}],
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
            }
          })(marker, i));

          markers.push(marker);

        }  
        map.fitBounds(bounds);
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>