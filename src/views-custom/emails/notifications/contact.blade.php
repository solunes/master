<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Estimado Administrador,</p>
		<p>Este es un email automático. Le escribimos porque un usuario llenó y envió el formulario de contacto en el sitio web. La puede ver dentro del siguiente link: <a href="{{ url('admin/model-list/form-contact') }}" target="_blank">{{ url('admin/model-list/form-contact') }}</a> (Debe iniciar sesión como administrador).</p>
		<p>El mensaje fue llenado de la siguiente manera:
		Nombre: {{ $item->name }}<br>
		Email: {{ $item->email }}<br>
		Teléfono: {{ $item->phone }}<br>
		Dirección: {{ $item->address }}<br>
		Mensaje: {{ $item->message }}<br>
		</p>
		<p>Saludos,</p>
		<p>Su Sistema</p>
	</body>
</html>
