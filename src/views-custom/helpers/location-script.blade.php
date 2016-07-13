<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBM9WvJSlDEv4CVMuCABBosg4Z-mcOB7hM&sensor=false&libraries=places"></script>
<script type="text/javascript">
    var markers = [];

    function initialize() {

        var bounds = new google.maps.LatLngBounds();

        var mapOptions = {
          center: new google.maps.LatLng(-16.51409139, -68.12485814),
          zoom: 6,
          //minZoom: 5,
          //maxZoom: 14,
          scrollwheel: false,
          mapTypeId: google.maps.MapTypeId.HYBRID,
          mapTypeControl: false,
          streetViewControl: false,
          zoomControl: true,
          zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
          }
        };
        var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

        map.controls[google.maps.ControlPosition.RIGHT_TOP].push(document.getElementById('legend')); 

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        var infowindow = new google.maps.InfoWindow();

        var marker, i;

        @if(count($section->vars['items'])>0)
          var locations = [
            @if($action=='edit')
              @foreach ($section->vars['items'] as $key => $point)
                @if($point->id!=$item->id)
                  ['<h3>{{ $point->name }}</h3>', {{ $point->latitude }}, {{ $point->longitude }}, '{{ $point->marker }}', {{ $key }}, {{ $point->id }}],
                @endif
              @endforeach
            @else
              @foreach ($section->vars['items'] as $key => $point)
                ['<h3>{{ $point->name }}</h3>', {{ $point->latitude }}, {{ $point->longitude }}, '{{ $point->marker }}', {{ $key }}, {{ $point->id }}],
              @endforeach
            @endif
          ];

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
                urlEdit = '{{ url("admin/location/edit/".$id) }}';
                urlDelete = '{{ url("admin/location/delete/".$id) }}';
                infowindow.setContent(locations[i][0]+' <a href="'+urlEdit+'/'+locations[i][5]+'">Editar</a> | <a href="'+urlDelete+'/'+locations[i][5]+'">Borrar</a>');
                infowindow.open(map, marker);
                marker.setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(function(){ 
                  marker.setAnimation(null); 
                }, 1500);
              }
            })(marker, i));

            markers.push(marker);

          }

          map.fitBounds(bounds);
        
        @endif

        @if($action=='edit')
            marker = new google.maps.Marker({
                id: {{ $item->id }},
                position: new google.maps.LatLng({{ $item->latitude }}, {{ $item->longitude }}),
                map: map,
                animation: google.maps.Animation.DROP
            });
        @else
            marker = new google.maps.Marker({
                id: 1000,
                @if(count($section->vars['items'])>0)
                position: bounds.getCenter(),
                @else
                position: new google.maps.LatLng(-16.51409139, -68.12485814),
                @endif
                map: map,
                animation: google.maps.Animation.DROP
            });
        @endif
        bounds.extend(marker.position);
        markers.push(marker);
        map.fitBounds(bounds);
        @if($action=='edit')
          latlongi = 'Editando: {{ $item->name }}';
        @else
          latlongi = 'Punto Nuevo';
        @endif
        infowindow.setContent(latlongi);
        infowindow.open(map, marker);

        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            var yeri = event.latLng;
            document.getElementById('latitude').value = yeri.lat().toFixed(6);
            document.getElementById('longitude').value = yeri.lng().toFixed(6);

            dec2dms();
        });
        google.maps.event.addListener(searchBox, 'places_changed', function() {
          var places = searchBox.getPlaces();

          for (var i = 0, place; place = places[i]; i++) {
            marker.setPosition(place.geometry.location);
            map.setCenter(place.geometry.location); // setCenter takes a LatLng object
            map.setZoom(13);
          }

        });

    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>