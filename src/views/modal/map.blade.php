<h3>Seleccionar Ubicación</h3>

<div id="modal-map" style="height: 400px;"></div>
<input id="modal-map-search" class="map-search-box" type="text" placeholder="Buscar">

<script type="text/javascript"> 
      var markers = [];

      function initializeModal() {

          var bounds = new google.maps.LatLngBounds();

          var mapOptions = {
            center: new google.maps.LatLng({{ $latitude }}, {{ $longitude }}),
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
          var map = new google.maps.Map(document.getElementById("modal-map"), mapOptions);

          // Create the search box and link it to the UI element.
          var input = document.getElementById('modal-map-search');
          var searchBox = new google.maps.places.SearchBox(input);
          map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

          google.maps.event.addDomListener(input, 'keydown', function(e) { 
            if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
          }); 

          var infowindow = new google.maps.InfoWindow();

          var marker, i;

          marker = new google.maps.Marker({
              id: 1,
              position: new google.maps.LatLng({{ $latitude }}, {{ $longitude }}),
              map: map,
              animation: google.maps.Animation.DROP
          });

          bounds.extend(marker.position);
          markers.push(marker);

          google.maps.event.addListener(map, 'click', function(event) {
              marker.setPosition(event.latLng);
              latlongi = 'Seleccione su Ubicación';
              var yeri = event.latLng;
              infowindow.setContent(latlongi);
              infowindow.open(map, marker);

              var coordinates = yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6);
              document.getElementById('{{ $name }}').value = coordinates;
              var link = document.getElementById('link-{{ $name }}');
              var link_text = "{{ url('admin/modal-map/'.$name) }}/"+ coordinates +"?lightbox[width]=800&lightbox[height]=500";
              link.innerHTML = "Editar Mapa ("+ coordinates +")";
              link.setAttribute('href', link_text);

          });
          google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();

            for (var i = 0, place; place = places[i]; i++) {
              marker.setPosition(place.geometry.location);
              map.setCenter(place.geometry.location); // setCenter takes a LatLng object
              map.setZoom(13);
              var yeri = place.geometry.location;
              var coordinates = yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6);
              document.getElementById('{{ $name }}').value = coordinates;
              var link = document.getElementById('link-{{ $name }}');
              var link_text = "{{ url('admin/modal-map/'.$name) }}/"+ coordinates +"?lightbox[width]=800&lightbox[height]=500";
              link.innerHTML = "Editar Mapa ("+ coordinates +")";
              link.setAttribute('href', link_text);
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

    initializeModal();
</script>