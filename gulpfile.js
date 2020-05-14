var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts(
        [
            'jquery/dist/jquery.min.js',
            'jquery-ui/jquery-ui.min.js',
            'jquery-ui/ui/i18n/datepicker-pt-BR.js',
            'bootstrap/dist/js/bootstrap.min.js',
            'bootstrap-fileinput/js/fileinput.js',
        ],
        'public/js/vendor.js',
        'resources/assets/vendor'
    )
    .sass('app.scss')
    .copy('resources/assets/vendor/bootstrap/dist/fonts/', 'public/fonts/bootstrap/')
    .copy('resources/assets/vendor/font-awesome/fonts/', 'public/fonts/')
    .copy('resources/assets/vendor/bootstrap-fileinput/img/loading.gif', 'public/img/');

});
