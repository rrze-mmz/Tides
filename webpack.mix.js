const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application.By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
// mix.autoload({
//     jquery: ['$', 'jQuery', 'window.jQuery']
// });

mix
    .js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('postcss-nested'),
        require('autoprefixer'),
        require('tailwindcss'),
    ])
    .version();

if (mix.inProduction()) {
    mix
        .version();
}
