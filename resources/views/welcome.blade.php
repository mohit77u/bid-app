<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Win Money</title>

        @vite('resources/sass/app.scss')
    </head>
    <body>
            <div id="app">
                <app-component></app-component>
            </div>

            @vite('resources/js/app.js')
    </body>
</html>
