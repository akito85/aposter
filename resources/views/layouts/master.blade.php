<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.header')
        <style>
            @yield('custom_css')
            .main-panel > .content { margin-top: 0; }
        </style>
    </head>
    <body>

        <main class="main-panel">
            @include('partials.nav')
            <div class="content">
                @yield('content')
            </div>
        </main>

        <script src="{{ asset('assets/vendors/paper/js/paper-dashboard.min.js') }}"></script>
        @yield('custom_js')
    </body>
</html>
