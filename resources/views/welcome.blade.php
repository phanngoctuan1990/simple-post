<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">

        <title>Simple Post</title>

        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
        <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
    </head>
    <body>
        <v-app id="app">
        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
