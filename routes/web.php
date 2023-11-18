<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('main.main');
// });

// Основные руты
Route::get('/', [MainController::class, 'index']);
Route::get('/all_articles', [MainController::class, 'show_all_articles']);
Route::get('/one_article', [MainController::class, 'show_one_article']);
Route::get('/about_us', [MainController::class, 'show_about_us']);
Route::get('/contacts', [MainController::class, 'show_contacts']);

// Руты для работы с пользователями
Route::get('/register', [AuthController::class, 'create']);
Route::post('/authenticate', [AuthController::class, 'authenticate']);

// Руты для работы со статьями
Route::resource('/article', ArticleController::class);