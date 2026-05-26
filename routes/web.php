<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', [ImageController::class, 'index'])->name('home');
Route::view('/help', 'help')->name('help');
Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
Route::post('/process', [ImageController::class, 'process'])->name('process');
Route::get('/download/{name}', [ImageController::class, 'download'])->name('download');
Route::get('/tools/{slug?}', [ImageController::class, 'tools'])->name('tools');

