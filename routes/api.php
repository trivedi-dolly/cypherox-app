<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('/signup',[AuthController::class,'signup']);
Route::post('/login',[AuthController::class,'login']);
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('/post',[UserController::class,'createPost']);
    Route::post('/comment',[UserController::class,'createComment']);
    Route::post('like-post-comments',[UserController::class,'likePostComment']);
    Route::get('/all-posts',[UserController::class,'getAllPosts']);
    Route::get('/posts/{id}',[UserController::class,'getPostsWithLikeComments']); 
    Route::post('/unlike-post-comments',[UserController::class,'unlikePostOrComment']);
});
