<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.header')
    </head>
    <body>
        @if (Auth::check())
        <main class="main-panel">
            <div class="content">
                @include('partials.sidebar')
                @yield('content')
            </div>
        </main>
        @else
        @yield('content')
        @endif

        @yield('custom_js')
    </body>
</html>
