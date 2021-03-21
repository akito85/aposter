const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(['public/assets/vendors/paper/js/core/jquery.min.js',
        'public/assets/vendors/daterangepicker/js/moment.min.js',
        'public/assets/vendors/paper/js/core/bootstrap.min.js',
        'public/assets/vendors/paper/js/core/popper.min.js',
        'public/assets/vendors/paper/js/plugins/chartjs.min.js',
        'public/assets/vendors/paper/js/plugins/chartjs-plugin-datalabels.min.js',
        'public/assets/vendors/paper/js/plugins/perfect-scrollbar.jquery.min.js',
        'public/assets/vendors/select2/js/select2.min.js',
        'public/assets/vendors/daterangepicker/js/daterangepicker.min.js'
        ], 'public/assets/js/app.js')
    .sass('resources/sass/app.scss', 'public/assets/css/app.css');
