<div class="container">
  @foreach($items as $item)
  	<h3>{{ $item->name }}</h3>
    <p><strong>Dirección:</strong> {{ $item->address }}</p>
    <p><strong>Ciudad:</strong> {{ $item->city }}</p>
    <p><strong>Persona de Contacto:</strong> {{ $item->contact_name }}</p>
    <p><strong>Email de Contacto:</strong> {{ $item->email }}</p>
    <p><strong>Teléfono:</strong> {{ $item->phone }}</p>
    <p><strong>Sitio Web:</strong> <a target="_blank" href="{{ $item->website }}">{{ $item->website }}</a></p>
    <div id="map-canvas"></div>
  @endforeach
</div>