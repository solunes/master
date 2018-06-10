<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="{{ url(elixir("assets/css/vendor.css")) }}">
    <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
</head>
<body>
  <div class="content-wrap pdf-wrap">  
    <h1>{{ $title }} | {{ $site->name }}</h1>
    <h2>1. Iniciar Sesión</h2>
    <p>Para iniciar sesión, se deberá ingresar a <a href="{{ url('admin') }}">{{ url('admin') }}</a>. Si es que la sesión aún no fue iniciada, tendrá que introducir el correo electrónico / carnet y la contraseña de su cuenta que le fue creada.</p>
    <p>Si es que se olvidó su contraseña, podrá recuperarla haciendo click en el botón de Olvide mi Contraseña, donde le enviará a su correo un link donde podrá cambiar su contraseña.</p>
    <h2>2. Conceptos Generales</h2>
    <p>A continuación se expondrán herramientas generales que podrán ser utilizadas a lo largo de toda la plataforma:</p>
    <ul>
      <li><strong>Filtros en Listas: </strong><br>
        Algunas de las tablas de la plataforma tienen filtros para poder ayudar en la navegación. Los filtros tienen la siguiente forma y pueden combinarse entre sí. Una vez se seleccionen los filtros, se deberá hacer click en la opción de buscar para que se desplieguen los resultados correspondientes en la tabla de abajo. Los gráfios (en caso de que existan), también responden a los filtros. Puede crear sus propios filtros así como eliminarlos, para armar sus propias combiniaciones.
      </li>
      <li><strong>Manejo de Tablas: </strong><br>
        Las tablas de todo el sistema pueden ser manipuladas de manera dinámica, es decir sobre la marcha. Se puede “Ordenar” por columnas de manera ascendente o descendente (las flechas indican esto), haciendo click en el título de la columna correspondiente. También se puede utilizar el “Buscador”, donde solo es necesario escribir y se irán mostrando los resultados que cumplan con esta búsqueda de manera automática y mientras se escriba. <br>
        Si es que las tablas son vistas desde tablets o teléfonos móviles, estas tienen un funcionamiento distinto puesto que las tablas no entran del todo en pantallas pequeñas. Se muestra la tabla resumida hasta donde entre y si se hace click en el botón azul de “+”en cada fila, se desplegará la información faltante de la fila.
      </li>
      <li><strong>Crear: </strong><br>
        Todas las tablas y listados, tienen un botón superior para poder crear nuevos elementos como se puede ver en la siguiente imagen.
      </li>
      <li><strong>Descargar en Excel: </strong><br>
        Todas las tablas pueden ser descargadas en Excel si se hace click en el botón “DESCARGAR”. Se enviará una consulta para descargar la tabla en un Excel que se generará con el nombre de la tabla y la fecha del día en que se descargue. Si es que se aplicaron filtros, se utilizarán al momento de descargar el excel.
      </li>
      <li><strong>Manejo de Formularios: </strong><br>
        Todos los formularios dentro del sitio web, ya sean al crear o editar serán validados antes de ser enviados y guardados. Si cumple con todos los requisitos, aparecerá un mensaje en verde indicando que los cambios fueron guardados. Si hay un error en la validación, le aparecerá un mensaje en rojo indicando los errores que hay que corregir.
      </li>
    </ul>
    <h2>2. Secciones Disponibles</h2>
    @include('master::pdf.manual-node', ['nodes_array'=>$nodes, 'last_count'=>'2.', 'count'=>0])
  </div>
</body>
</html>