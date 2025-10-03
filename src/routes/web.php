<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlocosController;

Route::redirect('/', '/home');

Route::view('/home', 'home')->name('home');

Route::view('/encomendas', 'encomendas')->name('encomendas');

Route::prefix('blocos')->group(function () {
    Route::get('/', [BlocosController::class, 'index'])->name('blocos.index');
    Route::get('/create', [BlocosController::class , 'blocosCreate'])->name('blocos.create');
    Route::post('/create', [BlocosController::class , 'blocosCreate'])->name('blocos.create');
    Route::put('/{id}/edit', [BlocosController::class, 'blocosEdit'])->name('blocos.edit');
    Route::delete('/{id}', [BlocosController::class, 'blocosDelete'])->name('blocos.delete');
});

Route::view('/torres', 'torres')->name('torres');

Route::view('/moradores', 'moradores')->name('moradores');
