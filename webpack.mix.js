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

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');

mix.js('resources/assets/js/init.js', 'public/js/init.js');

// MaterializeCSS
mix.js(['node_modules/materialize-css/dist/js/materialize.js'], 'public/js/materialize.js');
mix.styles(['node_modules/materialize-css/dist/css/materialize.css'], 'public/css/materialize.css');

// VueMaterial
mix.styles(['node_modules/vue-material/dist/vue-material.min.css'], 'public/css/vue-material.min.css');

// noUiSlider (Materializecss)
mix.js(['node_modules/materialize-css/extras/noUiSlider/nouislider.js'], 'public/js/nouislider.js');
mix.styles(['node_modules/materialize-css/extras/noUiSlider/nouislider.css'], 'public/css/nouislider.css');

