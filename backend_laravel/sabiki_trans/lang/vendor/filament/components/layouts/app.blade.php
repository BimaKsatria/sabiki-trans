<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sabiki Trans Admin</title>

       <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @filamentStyles
    </head>
    <body class="antialiased">
        {{ $slot }}
        @livewireScripts
        @filamentScripts
    </body>
</html>