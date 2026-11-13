<?php

require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
app()->setLocale(($argv[1] ?? 'id') === 'en' ? 'en' : 'id');
$source = file_get_contents(resource_path('views/admin/company/export_form.blade.php'));
$source = substr($source, strpos($source, "@push('scripts')"));
$source = str_replace(["@push('scripts')", '@endpush'], '', $source);
$__env = app('view');
ob_start();
eval('?>'.app('blade.compiler')->compileString($source));
$html = ob_get_clean();
preg_match('#<script>(.*?)</script>#s', $html, $matches);
echo $matches[1];
