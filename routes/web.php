<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JogadoresController;
use App\Http\Controllers\SorteiosController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function(){
    return view("welcome");
});

Route::get('/jogadores', [JogadoresController::class, 'index']);



Route::controller(JogadoresController::class)->group(function () {
    // Route::get('/jogadores', 'index');
    Route::post('jogadores/createUpdate', 'createUpdate')->name("jogadores.add-edit");
});

Route::controller(SorteiosController::class)->group(function () {
    Route::get('/sorteios', 'index')->name("sorteios.index");
    Route::post("/sorteios/sortear", 'sortear')->name("sorteio.sortear");
    Route::get('/sorteios/view', 'view')->name("sorteios.view");
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
