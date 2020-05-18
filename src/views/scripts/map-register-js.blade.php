<script type="text/javascript">
  var markers = [];
  function initialize() {
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
      center: new google.maps.LatLng({{ $map_coordinates['latitude'] }}, {{ $map_coordinates['longitude'] }}),
      zoom: {{ config('solunes.default_zoom') }},
      minZoom: 5,
      maxZoom: 18,
      scrollwheel: false,
      mapTypeId: {{ config('solunes.default_map') }},
      mapTypeControl: false,
      streetViewControl: false,
      zoomControl: true,
      zoomControlOptions: {
        style: google.maps.ZoomControlStyle.SMALL
      }
    };
    var map = new google.maps.Map(document.getElementById("map-map_coordinates"), mapOptions);

    // Create the search box and link it to the UI element.
    var input = document.getElementById('search-map_coordinates');
    /*var searchBox = new google.maps.places.SearchBox(input);*/


    var searchBox = new google.maps.places.Autocomplete(input);
    // Set initial restrict to the greater list of countries.
    @if(config('solunes.default_map_restrict_country'))
    searchBox.setComponentRestrictions({'country': ['{{ config("solunes.default_map_restrict_country") }}']});
    @endif
    // Specify only the data fields that are needed.
    //searchBox.setFields(['address_components', 'name']);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    google.maps.event.addDomListener(input, 'keydown', function(e) { 
      if (e.keyCode == 13) { 
          e.preventDefault(); 
      }
    }); 

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    var coordinates = document.getElementById('map_coordinates').value;
    var coordinates_arr = coordinates.split(';');
    var latitude = coordinates_arr[0];
    var longitude = coordinates_arr[1];

    @if(isset($map_coordinates['latitude'])&&isset($map_coordinates['longitude']))
        marker = new google.maps.Marker({
            id: 1,
            position: new google.maps.LatLng({{ $map_coordinates['latitude'] }}, {{ $map_coordinates['longitude'] }}),
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

    // Try HTML5 geolocation.
    @if(isset($map_coordinates['type'])&&$map_coordinates['type']=='default')
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };

        marker.setPosition(pos);
        infowindow.setContent('Seleccione su ubicación');
        infowindow.open(map, marker);
        map.setCenter(pos);
      }, function() {
        handleLocationError(true, infowindow, map, marker);
      });
    } else {
      // Browser doesn't support Geolocation
      handleLocationError(false, infowindow, map, marker);
    }
    @endif

    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        latlongi = 'Seleccione su Ubicación';
        var yeri = event.latLng;
        infowindow.setContent(latlongi);
        infowindow.open(map, marker);
        $('#map_coordinates').val(yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6)).trigger('change');;
    });
    searchBox.addListener('place_changed', function() {
      var place = searchBox.getPlace();
      //for (var i = 0, place; place = places[i]; i++) {
        marker.setPosition(place.geometry.location);
        map.setCenter(place.geometry.location); // setCenter takes a LatLng object
        map.setZoom(16);
        var yeri = place.geometry.location;
        $('#map_coordinates').val(yeri.lat().toFixed(6)+ ";" +yeri.lng().toFixed(6)).trigger('change');;
      //}
    });

    map.fitBounds(bounds);

    zoomChangeBoundsListener = google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
        if (this.getZoom()){
          this.setZoom({{ config('solunes.default_zoom') }});
        }
    });
    setTimeout(function(){google.maps.event.removeListener(zoomChangeBoundsListener)}, 2000);
  }
  google.maps.event.addDomListener(window, 'load', initialize);

  function handleLocationError(browserHasGeolocation, infoWindow, map, marker) {
    infoWindow.setContent(browserHasGeolocation ?
                          'Error: Hubo un error con el servicio de Geolocalización.' :
                          'Error: Su navegador no soporta la Geolocalización.');
    infoWindow.open(map, marker);
  }

</script>