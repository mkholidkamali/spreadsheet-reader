<?php

use App\Http\Controllers\SpreadsheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/data', function (SpreadsheetController $test) {
    return $test->getSpreadSheet();
});
