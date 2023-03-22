<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['revalidate_back_history']], function () {
	Route::get('/', 'HomeController@home')->name('home');
	Route::group(['prefix' => 'auth', 'middleware' => ['custom_guest']], function () {
		Route::get('/registration', 'AuthController@getRegister')->name('getRegister');
		Route::post('/registration', 'AuthController@postRegister')->name('postRegister');
		Route::post('/check_email_unique', 'AuthController@check_email_unique')->name('check_email_unique');
		Route::get('/verify-email/{verification_code}', 'AuthController@verify_email')->name('verify_email');
		Route::get('/login', 'AuthController@getLogin')->name('getLogin');
		Route::post('/login', 'AuthController@postLogin')->name('postLogin');
		Route::get('/forget-password', 'AuthController@getForgetPassword')->name('getForgetPassword');
		Route::post('/forget-password', 'AuthController@postForgetPassword')->name('postForgetPassword');
		Route::get('/reset-password/{reset_code}', 'AuthController@getResetPassword')->name('getResetPassword');
		Route::post('/reset-password/{reset_code}', 'AuthController@postResetPassword')->name('postResetPassword');
	});
	Route::get('/auth/logout', 'AuthController@logout')->name('logout')->middleware('custom_auth');
	Route::group(['prefix' => 'profile', 'middleware' => ['custom_auth']], function () {
		Route::get('/dashboard', 'ProfileController@dashboard')->name('dashboard');
		Route::get('/edit-profile', 'ProfileController@edit_profile')->name('edit_profile');
		Route::put('/edit-profile', 'ProfileController@update_profile')->name('update_profile');
		Route::get('/change-password', 'ProfileController@change_password')->name('change_password');
		Route::post('/update-password', 'ProfileController@update_password')->name('update_password');
	});
	Route::get('userManagement', [App\Http\Controllers\UserManagementController::class, 'index'])->middleware('auth')->name('userManagement');
	Route::get('add', [App\Http\Controllers\UserManagementController::class, 'addNewUser'])->middleware('auth')->name('add');
	Route::post('save', [App\Http\Controllers\UserManagementController::class, 'newUserSave'])->name('save');
	Route::get('edit/{id}', [App\Http\Controllers\UserManagementController::class, 'edit_profile'])->name('edit');
	Route::get('delete/{id}', [App\Http\Controllers\UserManagementController::class, 'delete'])->middleware('auth')->name(('delete'));
	Route::get('acitvity', 'UserManagementController@activitylog')->name('activity');

});