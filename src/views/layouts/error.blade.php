<!DOCTYPE html>
<html>
    <head>
        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{ asset('assets/admin/css/admin.css') }}">
        <link rel="stylesheet" href="{{ url(elixir("assets/css/main.css")) }}">
        
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #008319;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .content p {
                font-weight: 600;
            }

            .title {
                font-size: 72px;
                margin-top: 30px;
            }
            .top {
                background: #008319;
                color: #fff;
                text-align: center;
                height: 250px;
                padding: 40px;
            }
            .top_title {
                font-size: 150px;
                font-weight: 600;
                padding-top: 20px;
            }
            .button {
                background: #008319;
                color: #fff;
                display: inline-block;
                padding: 10px;
                font-weight: 600;
            }
            a:hover .button {
                background: #06A024;
            }
        </style>
    </head>
    <body class="error-site">
        <div class="container">
            <div class="top">
                <div class="top_title">{{ trans('master::admin.error_title') }}</div>
            </div>
            <div class="content">
                <div class="title">
                  @yield('title')
                </div>
                <p>
                  @yield('description')
                </p>
                <a href="{{ url('') }}"><div class="button">{{ trans('master::admin.error_button') }}</div></a>
            </div>
        </div>
    </body>
</html>