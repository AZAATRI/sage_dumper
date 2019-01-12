/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');
// require jQuery normally

const $ = require('jquery');
// create global $ and jQuery variables
global.$ = global.jQuery = $;
require('bootstrap');

require('bootstrap/dist/css/bootstrap.min.css');
require('@fortawesome/fontawesome-free');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('../css/app.css');
// any CSS you require will output into a single css file (app.css in this case)

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
