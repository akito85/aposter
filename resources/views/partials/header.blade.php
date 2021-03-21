<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}</title>

<!-- Scripts -->
<script src="{{ secure_asset('assets/js/app.js') }}"></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700,800" rel="styleshet">

<!-- Styles -->
<link href="{{ secure_asset('assets/css/app.css') }}" rel="stylesheet">