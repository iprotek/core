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

mix//.js('resources/js/app.js', 'public/js')
    //.js('resources/js/app-licenses.js', 'public/js')
    //.js('resources/js/languages.js', 'public/js') 
    //.js('resources/js/manage/dashboard.js', 'public/js/manage')
    //.js('resources/js/manage/account-emails.js', 'public/js/manage')
    //.js('resources/js/manage/header-footers.js', 'public/js/manage')
    //.js('resources/js/manage.js', 'public/js/manage')
    .js('resources/js/manage/notification.js', 'public/js/manage')
    .js('resources/js/manage/notification-view.js', 'public/js/manage')
    //.js('resources/js/manage/settings.js', 'public/js/manage')
    //.js('resources/js/pay-forgot-password.js', 'public/js')
    //.js('resources/js/branch.js', 'public/js')
    //.js('resources/js/sharedaccount.js', 'public/js')
 
    //.js('resources/js/homepage.js', 'public/js')
    .vue();
    //.sass('resources/sass/app.scss', 'public/css');
