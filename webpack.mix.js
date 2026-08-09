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

// Pipeline LAMA — tetap dipertahankan, ini yang sudah terbukti jalan
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

// TAMBAHAN — template Bootstrap 5 baru (resources/assets/*)
// Pakai copyDirectory, BUKAN mix.js()/mix.css(), karena:
// 1. File sudah minified (alpha.min.js) — tidak perlu di-bundle ulang
// 2. Plugin-plugin template biasanya pakai variabel global (window.X),
//    bukan ES module — kalau dipaksa lewat webpack bundling/babel,
//    berisiko rusak karena Babel/Webpack mengubah scope variable
// 3. copyDirectory murni menyalin struktur folder apa adanya ke public/,
//    referensi path di HTML template asli (biasanya "assets/js/...",
//    "assets/css/...") tetap valid tanpa perlu diubah manual satu-satu
mix.copyDirectory('resources/assets', 'public/assets');