<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlocosController;
use App\Http\Controllers\TorresController;
use App\Http\Controllers\MoradoresController;
use App\Http\Controllers\EncomendasController;
use App\Http\Controllers\ApartamentosController;
use App\Http\Controllers\HomeController;

Route::redirect('/', 'home');

Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

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
    Route::get('/search', [ApartamentosController::class, 'apartamentoSearch'])->name('apartamentos.search');
});

Route::prefix('moradores')->group(function () {
    Route::get('/', [MoradoresController::class, 'index'])->name('moradores.index');
    Route::get('/create', [MoradoresController::class , 'moradoresCreate'])->name('moradores.create');
    Route::post('/create', [MoradoresController::class , 'moradoresCreate'])->name('moradores.store');
    Route::post('import', [MoradoresController::class, 'moradoresImport'])->name('moradores.import');
    Route::put('/{id}/edit', [MoradoresController::class, 'moradoresEdit'])->name('moradores.edit');
    Route::delete('/{id}', [MoradoresController::class, 'moradoresDelete'])->name('moradores.delete');
    Route::get('/search', [MoradoresController::class, 'moradorSearch'])->name('moradores.search');
});

Route::prefix('encomendas')->group(function () {
    Route::get('/', [EncomendasController::class, 'index'])->name('encomendas.index');
    Route::get('/create', [EncomendasController::class, 'encomendasCreate'])->name('encomendas.create');
    Route::post('/create', [EncomendasController::class, 'encomendasCreate'])->name('encomendas.store');
    Route::put('/{id}/edit', [EncomendasController::class, 'encomendasEdit'])->name('encomendas.edit');
    Route::delete('/{id}', [EncomendasController::class, 'encomendasDelete'])->name('encomendas.delete');
    Route::get('/search', [EncomendasController::class, 'encomendaSearch'])->name('encomendas.search');
});