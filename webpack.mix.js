const mix = require("laravel-mix");

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

mix
  .js("resources/assets/js/app.js", "public/js")
  .sass("resources/assets/sass/app.scss", "public/css")
  .sass("resources/assets/sass/home.scss", "public/css/pages")
  .sass("resources/assets/sass/amipersonas.scss", "public/css/pages")
  .sass("resources/assets/sass/moreinformation.scss", "public/css/pages")
  .sass("resources/assets/sass/hego.scss", "public/css/pages")
  .sass("resources/assets/sass/hegoinformation.scss", "public/css/pages")
  .vue();
