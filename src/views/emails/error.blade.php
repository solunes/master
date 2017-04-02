<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Error en {{ $app_name }}</h2>

		<div>
			<p><strong>Fecha:</strong> {{ date('Y-m-d H:i:s') }}</p>
			<p><strong>URL:</strong> {{ $url }}</p>
			<p><strong>Usuario:</strong> {{ $user }}</p>
			<p><strong>Se detectó un error con el siguiente código:</strong></p>
			<p><i>{!! $log !!}</i></p>
		</div>
	</body>
</html>
