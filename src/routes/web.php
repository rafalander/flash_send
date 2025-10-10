<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlocosController;
use App\Http\Controllers\TorresController;
use App\Http\Controllers\MoradoresController;
use App\Http\Controllers\EncomendasController;
use App\Http\Controllers\ApartamentosController;

Route::redirect('/', '/home');

Route::view('/home', 'home')->name('home');

Route::view('/encomendas', 'encomendas')->name('encomendas');

Route::prefix('blocos')->group(function () {
    Route::get('/', [BlocosController::class, 'index'])->name('blocos.index');
    Route::get('/create', [BlocosController::class , 'blocosCreate'])->name('blocos.create');
    Route::post('/create', [BlocosController::class , 'blocosCreate'])->name('blocos.store');
    Route::put('/{id}/edit', [BlocosController::class, 'blocosEdit'])->name('blocos.edit');
    Route::delete('/{id}', [BlocosController::class, 'blocosDelete'])->name('blocos.delete');
});

Route::prefix('torres')->group(function () {
    Route::get('/',[TorresController::class, 'index'])->name('torres.index');
    Route::get('/create', [TorresController::class , 'torresCreate'])->name('torres.create');
    Route::post('/create', [TorresController::class , 'torresCreate'])->name('torres.store');
    Route::put('/{id}/edit', [TorresController::class, 'torresEdit'])->name('torres.edit');
    Route::delete('/{id}', [TorresController::class, 'torresDelete'])->name('torres.delete');
});

Route::prefix('apartamentos')->group(function () {
    Route::get('/', [ApartamentosController::class, 'index'])->name('apartamentos.index');
    Route::get('/create', [ApartamentosController::class , 'apartamentosCreate'])->name('apartamentos.create');
    Route::post('/create', [ApartamentosController::class , 'apartamentosCreate'])->name('apartamentos.store');
    Route::post('import', [ApartamentosController::class, 'apartamentosImport'])->name('apartamentos.import');
    Route::put('/{id}/edit', [ApartamentosController::class, 'apartamentosEdit'])->name('apartamentos.edit');
    Route::delete('/{id}', [ApartamentosController::class, 'apartamentosDelete'])->name('apartamentos.delete');
});

Route::view('/moradores', 'moradores')->name('moradores');

