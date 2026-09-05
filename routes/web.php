<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PublicDocumentController;




Route::get('/', function () {
    return redirect()->route('login');
});




Route::middleware('guest')->group(function () {

    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.store');

});




Route::post('/logout', [
    AuthController::class,
    'logout'
])->middleware('auth')->name('logout');




Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');




    Route::get('/documents', [
        DocumentController::class,
        'index'
    ])->name('documents.index');


    Route::get('/documents/create', [
        DocumentController::class,
        'create'
    ])->name('documents.create');


    Route::post('/documents', [
        DocumentController::class,
        'store'
    ])->name('documents.store');


    Route::get('/documents/{document}', [
        DocumentController::class,
        'show'
    ])->name('documents.show');


    Route::delete('/documents/{document}', [
        DocumentController::class,
        'destroy'
    ])->name('documents.destroy');

});




Route::get('/document/{token}', [
    PublicDocumentController::class,
    'show'
])->name('public.document');


Route::get('/document/{token}/download', [
    PublicDocumentController::class,
    'downloadOriginal'
])->name('public.document.download');


Route::post('/document/{token}/sign', [
    PublicDocumentController::class,
    'sign'
])->name('public.document.sign');

Route::get('/document/{token}/download', [
    PublicDocumentController::class,
    'downloadOriginal'
])->name('public.document.download');
