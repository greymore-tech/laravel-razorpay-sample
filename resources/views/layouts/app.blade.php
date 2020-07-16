<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.laravel = {csrfToken: '{{ csrf_token() }}'}</script>

        <title>{{ config('app.name', 'Product Page') }}</title>

        {{-- CSS --}}
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}">

        {{-- JS --}}
        <script src="{{ asset('js/app.js') }}" async defer></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            @yield('content')
        </div>
    </body>
</html>
