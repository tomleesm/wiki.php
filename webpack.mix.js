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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/edit.js', 'public/js').version();

mix.copy('resources/js/prism.js', 'public/js/prism.js')
   .copy('resources/sass/prism.css', 'public/css/prism.css');

mix.copyDirectory('resources/img', 'public/img');
