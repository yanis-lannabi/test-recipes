<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config('app.name')}}</title>
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><path fill='%23FF6B35' d='M12.5,1.5C10.73,1.5 9.17,2.67 8.42,4.37C8.34,4.56 8.5,4.75 8.7,4.75H16.3C16.5,4.75 16.66,4.56 16.58,4.37C15.83,2.67 14.27,1.5 12.5,1.5M8.75,5.75V7.25C8.75,7.66 9.09,8 9.5,8H15.5C15.91,8 16.25,7.66 16.25,7.25V5.75H20.5C20.91,5.75 21.25,6.09 21.25,6.5V19.5C21.25,20.88 20.13,22 18.75,22H6.25C4.87,22 3.75,20.88 3.75,19.5V6.5C3.75,6.09 4.09,5.75 4.5,5.75H8.75M12.5,10A1.5,1.5 0 0,0 11,11.5A1.5,1.5 0 0,0 12.5,13A1.5,1.5 0 0,0 14,11.5A1.5,1.5 0 0,0 12.5,10Z'/></svg>">
        @vite(['resources/js/app.ts'])
    </head>
    <body>
        <div id="app"></div>
    </body>
</html>
