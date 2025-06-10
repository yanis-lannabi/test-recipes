<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>{{ config('app.name', 'Les recettes de Fullstack') }}</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">
        @vite('resources/js/app.ts')
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
