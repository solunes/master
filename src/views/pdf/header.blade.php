<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
      .pdf-header { text-align: center; width: 100%; margin-bottom: {{ intval($pdf_options['margin-top']) }}px; }
      .pdf-header img { width: auto; max-height: {{ intval($pdf_options['margin-top'])*2 }}px; margin-bottom: {{ intval($pdf_options['margin-top'])*.5 }}px; }
      .pdf-header .border { width: 100%; background: #ddd; height: 3px; }
      .pdf-header .subborder { margin-left: 45%; width: 10%; background: {{ config('solunes.app_color') }}; height: 3px; }
    </style>
</head>
<body style="margin:0; padding:0;">
  <div class="pdf-header">  
    <img src="{{ asset('assets/img/logo.png') }}" />
    <div class="border"><div class="subborder"></div></div>
  </div>
  <br>
</body>
</html>