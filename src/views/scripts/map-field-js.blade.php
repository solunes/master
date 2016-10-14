@if(isset($map_array))
  <script type="text/javascript">
    @foreach($map_array as $item)
      <?php $field_name = $item->name; ?>
      var markers = [];

      function initialize() {

          var bounds = new google.maps.LatLngBounds();

          var mapOptions = {
            center: new google.maps.LatLng(-16.51409139, -68.12485814),
            zoom: 15,
            minZoom: 5,
            maxZoom: 20,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            mapTypeControl: false,
            streetViewControl: false,
            zoomControl: true,
            zoomControlOptions: {
              style: google.maps.ZoomControlStyle.SMALL
            }
          };
          var map = new google.maps.Map(document.getElementById("map-{{ $item->name }}"), mapOptions);

          // Create the search box and link it to the UI element.
          var input = document.getElementById('search-{{ $item->name }}');
          var searchBox = new google.maps.places.SearchBox(input);
          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

          google.maps.event.addDomListener(input, 'keydown', function(e) { 
            if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
          }); 

          var infowindow = new google.maps.InfoWindow();

          var marker, i;
          var coordinates = document.getElementById('{{ $item->name }}').value;
          var coordinates_arr = coordinates.split(';');
          var latitude = coordinates_arr[0];
          var longitude = coordinates_arr[1];

          @if($i&&$i->$field_name)
            <?php $array = explode(';',$i->$field_name); ?>
              marker = new google.maps.Marker({
                  id: {{ $i->id }},
                  position: new google.maps.LatLng({{ $array[0] }}, {{ $array[1] }}),
                  map: map,
                  animation: google.maps.Animation.DROP
              });
          @else
              marker = new google.maps.Marker({
                  id: 1,
                  position: new google.maps.LatLng(latitude, longitude),
                  map: map,
                  animation: google.maps.Animation.DROP
              });
          @endif

          bounds.extend(marker.position);
          markers.push(marker);

          google.maps.event.addListener(map, 'click', function(event) {
              marker.setPosition(event.latLng);
              latlongi = 'Seleccione su Ubicación';
              var yeri = event.latLng;
              infowindow.setContent(latlongi);
              infowindow.open(map, marker);

              document.getElementById('{{ $item->name }}').value = yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6);
          });
          google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();

            for (var i = 0, place; place = places[i]; i++) {
              marker.setPosition(place.geometry.location);
              map.setCenter(place.geometry.location); // setCenter takes a LatLng object
              map.setZoom(13);
              var yeri = place.geometry.location;
              document.getElementById('{{ $item->name }}').value = yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6);
            }
          });

          map.fitBounds(bounds);

          zoomChangeBoundsListener = google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
              if (this.getZoom()){
                this.setZoom(16);
              }
          });
          setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);

      }

      google.maps.event.addDomListener(window, 'load', initialize);
    @endforeach
  </script>
@endif