<?php

use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('import.form');

Route::post('/', ExcelController::class)->name('import.store');
