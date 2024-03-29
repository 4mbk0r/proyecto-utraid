const mix = require("laravel-mix");

require('dotenv').config();
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */


mix.setResourceRoot('/main/public')

mix.js('resources/js/app.js', 'public/js').vue().sourceMaps()
    .postCss('resources/css/app.css', 'public/css', [
        //require('postcss-import'),
        //require('tailwindcssnpm'),
        //require('autoprefixer'),

    ])
    .webpackConfig(
        require('./webpack.config'));
if (mix.inProduction()) {
    mix.version();
}
