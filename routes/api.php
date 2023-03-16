<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/register', 'Auth\RegisterController@register');
Route::post('/login', 'Auth\LoginController@postLogin');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/logout', 'Auth\LogoutController@logout');
    Route::post('/post', 'JobPosting\JobPostingController@create');
    Route::get('/all-posts', 'JobPosting\JobPostingController@getJobPosting');
    Route::patch('/post/{id}', 'Admin\AdminController@approvePost');
    Route::post('/post/{id}', 'JobPosting\JobPostingController@update');
    Route::delete('/post/{id}', 'JobPosting\JobPostingController@destroy');
    Route::post('/comment/{id}', 'JobPosting\CommentController@storeComment');
    Route::post('/update-comment/{id}', 'JobPosting\CommentController@updateComment');
    Route::delete('/comment/{id}', 'JobPosting\CommentController@destroyComment');
    Route::post('/apply/{id}', 'JobApply\JobApplyController@apply');
});
Route::get('/search', 'Seeker\SearchJobController@searchJob');


// Route::get('/test', 'Auth\RegisterController@test');
