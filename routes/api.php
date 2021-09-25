<?php

use App\Http\Controllers\ApiBookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/books', [ApiBookController::class, 'index']);
Route::get('/books/catdetails', [ApiBookController::class, 'catdetails']);
Route::get('/books/authdetails', [ApiBookController::class, 'authdetails']);
Route::post('/books', [ApiBookController::class, 'store']);
Route::post('/books/update/{id}', [ApiBookController::class, 'update']);
Route::get('/books/show/{id}', [ApiBookController::class, 'show']);
Route::get('/books/delete/{id}', [ApiBookController::class, 'delete']);
Route::get('/books/archive', [ApiBookController::class, 'archive']);
Route::get('/books/restore/{id}', [ApiBookController::class, 'restore']);

