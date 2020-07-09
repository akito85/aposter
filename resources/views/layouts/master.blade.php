<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.header')
    </head>
    <body>
        <main class="py-4">
            @include('partials.sidebar')
            @yield('content')
        </main>
    </body>
</html>
