<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(CategoryController::class)->prefix('category')->group(function () {
    Route::post('list',  'list');
    Route::post('create', 'create');
    Route::get('get/{id}',  'get');
    Route::post('update/{id}', 'update');
    Route::post('delete/{id}', 'delete');
});

Route::controller(PostController::class)->prefix('post')->group(function () {
    Route::post('list' ,'list');
    Route::post('create', 'create');
    Route::get('get/{id}',  'get');
    Route::post('update/{id}', 'update');
    Route::post('delete/{id}', 'delete');
});