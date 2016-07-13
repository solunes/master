<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

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
    <body>
        <div class="container">
            <div class="top">
                <div class="top_title">{{ trans('admin.sorry') }}</div>
            </div>
            <div class="content">
                <div class="title">{{ trans('admin.404') }}</div>
                <p>{{ trans('admin.404_description') }}.</p>
                <a href="{{ url('') }}"><div class="button">{{ trans('admin.404_button') }}</div></a>
            </div>
        </div>
    </body>
</html>
