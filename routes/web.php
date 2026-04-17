<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NfseProcessorController;

Route::get('/', [NfseProcessorController::class, 'index'])->name('nfse.index');
Route::post('/process', [NfseProcessorController::class, 'process'])->name('nfse.process');
Route::post('/download', [NfseProcessorController::class, 'download'])->name('nfse.download');
