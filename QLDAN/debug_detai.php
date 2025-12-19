<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

file_put_contents('debug_output.txt', "DeTai Count: " . \App\Models\DeTai::count() . "\n" . "DeTai IDs: " . implode(', ', \App\Models\DeTai::pluck('MaDeTai')->toArray()) . "\n" . "DeTai with MaCB=NULL: " . \App\Models\DeTai::whereNull('MaCB')->count() . "\n");
