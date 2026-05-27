<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

Route::get('/', [ImageController::class, 'index'])->name('home');
Route::get('/basics', [ImageController::class, 'basics'])->name('basics');
Route::get('/multi-operation', [ImageController::class, 'multiOperation'])->name('multi-operation');
Route::view('/help', 'help')->name('help');
Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
// Provide a safe GET entry for /process (redirect to home) to avoid MethodNotAllowed
Route::get('/process', function () {
	return redirect()->route('home');
})->name('process.page');
Route::post('/process', [ImageController::class, 'process'])->name('process');
Route::get('/download/{name}', [ImageController::class, 'download'])->name('download');
Route::get('/tools/{slug?}', [ImageController::class, 'tools'])->name('tools');

