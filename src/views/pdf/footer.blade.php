<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Oswald', sans-serif; }
      .pdf-header { width: 100%; padding-top: {{ intval($pdf_options['margin-bottom']) }}px; }
      .pdf-header h3 { color: #999; margin-top: {{ intval($pdf_options['margin-bottom']) }}px; margin-bottom: 0; }
      .pdf-header span { margin-left: 20px; margin-right: 10px; color: {{ config('solunes.app_color') }}; }
      .pdf-header .border { width: 100%; background: #ddd; height: 3px; }
      .pdf-header .subborder { width: 10%; background: {{ config('solunes.app_color') }}; height: 3px; }
    </style>
</head>
<body style="margin:0; padding:0;">
  <div class="pdf-header">  
    <div class="border"><div class="subborder"></div></div>
    <h3>{{ $site_name }}<span> | </span>{{ $title }}<span> | </span>{{ date('d/m/Y H:i') }}</h3>
  </div>
  <br>
</body>
</html>