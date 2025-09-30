<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');

Route::view('/home', 'home')->name('home');

Route::view('/encomendas', 'encomendas')->name('encomendas');

Route::view('/blocos', 'blocos')->name('blocos');

Route::view('/torres', 'torres')->name('torres');

Route::view('/moradores', 'moradores')->name('moradores');
