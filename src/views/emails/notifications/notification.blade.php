<!DOCTYPE html>
<html lang="es-ES">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Estimado(a) {{ $user->name }},</p>
		<p>Le informamos que su registro en el "PROGRAMA MUNICIPAL DE RECONOCIMIENTO Y FOMENTO A INICIATIVAS SOSTENIBLES" fue aprobado. Para poder llenar el siguiente formulario de postulación, debe iniciar sesión en nuestra plataforma accediendo al siguiente link.</p>

		@if($model=='registry-a')
			<p><strong>Link de Postulación A:</strong> <a href="{{ url('postulacion-a?postulation_a='.$pos->id) }}" target="_blank">{{ url('postulacion-a?postulation_a='.$pos->id) }}</a></p>
		@else
			<p><strong>Link de Postulación B:</strong> <a href="{{ url('postulacion-b?postulation_b='.$pos->id) }}" target="_blank">{{ url('postulacion-b?postulation_b='.$pos->id) }}</a></p>
		@endif

		@if($user_exists)
			<p>Se detectó que ya hizo un registro anteriormente, por lo tanto, podrá utilizar la misma contraseña junto a su correo electrónico "{{ $user->email }}".</p>
		@else
		<p>Para acceder a su cuenta debe ingresar los siguientes datos de acceso:</p>
		<p><strong>Email:</strong> {{ $user->email }}<br>
		<strong>Contraseña:</strong> {{ $password }}</p>
		<p>Le recomendamos cambiar su contraseña la primera vez que inicie sesión.</p>
		@endif
		<p>Saludos cordiales</p>
		<p>Dirección de Ambiente<br>Muy Ilustre Municialidad de Guayaquil</p>
	</body>
</html>
