<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}</title>

<!-- Scripts -->
<script src="{{ asset('assets/vendors/paper/js/core/jquery.min.js') }}" defer></script>
<script src="{{ asset('assets/vendors/paper/js/core/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('assets/vendors/paper/js/core/popper.min.js') }}" defer></script>
<script src="{{ asset('assets/vendors/paper/js/plugins/chartjs.min.js') }}" defer></script>
<script src="{{ asset('assets/vendors/paper/js/paper-dashboard.min.js') }}" defer></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700,800" rel="styleshet">

<!-- Styles -->
<link href="{{ asset('assets/vendors/paper/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendors/paper/css/paper-dashboard.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">