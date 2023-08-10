<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\ArticalController;
use App\Http\Controllers\admin\links\NewsController;
use App\Http\Controllers\admin\links\TrustController;
use App\Http\Controllers\admin\QuestionAnswerController;

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
//     return view('welcome');
// });

Auth::routes();

Route::group(['middleware' => ['admin.auth']], function () {
    Route::get('/',[AdminController::class,'index'])->name('/');
    Route::post('/logout',[LoginController::class,'logout'])->name('logout');
    Route::get('unverfied/provider',[AdminController::class,'getunverifiedProviders'])->name('unverfied.provider');
    Route::get('verify_account/{id}',[AdminController::class,'editAccount'])->name('verify.provider');
    Route::get('get_categories',[AdminController::class,'gitCategories'])->name('get.categories');
    Route::get('create/category',[AdminController::class,'createCategory'])->name('create.category');
    Route::post('category/store',[AdminController::class,'storeCategory'])->name('store.category');

    Route::get('edit/category/{id}',[AdminController::class,'editCategory'])->name('edit.category');
    Route::post('category/update/{id}',[AdminController::class,'updateCategory'])->name('update.category');

    Route::get('get/insurance',[AdminController::class,'gitInsurance'])->name('get.insurance');
    Route::get('get/advertisement',[AdminController::class,'advertisement'])->name('get.advertisement');
    Route::get('create/advertisement',[AdminController::class,'createAdvertisement'])->name('create.advertisement');
    Route::post('store/advertisement',[AdminController::class,'storeAdvertisment'])->name('store.advertisement');
    Route::get('destroy/advertisement/{id}',[AdminController::class,'deleteAdvertisment'])->name('destroy.advertisement');
    Route::get('edit/advertisement/{id}',[AdminController::class,'editAdvertisment'])->name('edit.advertisement');
    Route::post('update/advertisement/{id}',[AdminController::class,'updateAdvertisment'])->name('update.advertisement');

    Route::get('get/articals',[ArticalController::class,'index'])->name('get.articals');
    Route::get('create/artical',[ArticalController::class,'create'])->name('create.artical');
    Route::post('store/artical',[ArticalController::class,'store'])->name('store.artical');
    Route::get('edit/artical/{id}',[ArticalController::class,'edit'])->name('edit.artical');
    Route::post('update/artical/{id}',[ArticalController::class,'update'])->name('update.artical');
    Route::get('destroy/artical/{id}',[ArticalController::class,'destroy'])->name('destroy.artical');

    Route::get('get/question',[QuestionAnswerController::class,'index'])->name('get.question');
    Route::get('create/question',[QuestionAnswerController::class,'create'])->name('create.question');
    Route::post('store/question',[QuestionAnswerController::class,'store'])->name('store.question');
    Route::get('edit/question/{id}',[QuestionAnswerController::class,'edit'])->name('edit.question');
    Route::post('update/question/{id}',[QuestionAnswerController::class,'update'])->name('update.question');
    Route::get('destroy/question/{id}',[QuestionAnswerController::class,'destroy'])->name('destroy.question');

    Route::resource('news', NewsController::class);
    Route::resource('trusted-providers', TrustController::class);
});
