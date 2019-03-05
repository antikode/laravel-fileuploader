let mix = require('laravel-mix');

mix.js([
    'src/resources/assets/js/jquery.fileuploader.min.js',
    'src/resources/assets/js/fileuploader.js'
], 'src/public/assets/js/app.js')
.styles([
    'src/resources/assets/css/jquery.fileuploader.min.css',
], 'src/public/assets/css/app.css')
.styles([
    'src/resources/assets/css/font/font-fileuploader.css'
], 'src/public/assets/css/font/font.css')
.copyDirectory('src/resources/assets/css/font', 'src/public/assets/css/font');