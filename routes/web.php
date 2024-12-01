<?php

use App\Http\Controllers\SpreadsheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/data-download', function (SpreadsheetController $test) {
    return $test->getSpreadSheetByDownload();
});

Route::get('/data-google-read', function (SpreadsheetController $test) {
    return $test->getSpreadSheetByDownload();
});
