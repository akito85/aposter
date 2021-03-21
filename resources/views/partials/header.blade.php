<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}</title>

<!-- Scripts -->
<script src="{{ secure_asset('assets/vendors/paper/js/core/jquery.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/daterangepicker/js/moment.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/paper/js/core/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/paper/js/core/popper.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/paper/js/plugins/chartjs.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/paper/js/plugins/chartjs-plugin-datalabels.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/paper/js/plugins/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/select2/js/select2.min.js') }}"></script>
<script src="{{ secure_asset('assets/vendors/daterangepicker/js/daterangepicker.min.js') }}"></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Montserrat:200,400,700,800" rel="styleshet">

<!-- Styles -->
<link href="{{ secure_asset('assets/vendors/paper/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ secure_asset('assets/vendors/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ secure_asset('assets/vendors/daterangepicker/css/daterangepicker.min.css') }}" rel="stylesheet">
<link href="{{ secure_asset('assets/vendors/paper/css/paper-dashboard.min.css') }}" rel="stylesheet">