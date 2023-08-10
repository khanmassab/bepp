<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\ChatController;
use App\Http\Controllers\api\v1\CustomeController;
// use App\Http\Controllers\api\v1\ProfileController;
use App\Http\Controllers\api\v1\CustomerController;
use App\Http\Controllers\api\v1\ProviderController;
use App\Http\Controllers\admin\links\NewsController;
use App\Http\Controllers\api\v1\InsuranceController;
use App\Http\Controllers\api\v1\HelpAndAdviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/get_categories', [CustomeController::class, 'getCategories']);
Route::get('/get_callback_times', [CustomeController::class, 'getCallbackTimes']);

Route::group(['middleware' => 'auth:api'], function(){

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/verify_account', [AuthController::class, 'verifyAccount'])->name('verify_account'); //register otp
    Route::group(['middleware' => 'isverify'],function(){
        // Route::post('/upload_profile_picture', [ProfileController::class, 'uploadProfilePicture']);
        // Route::get('/get_profile_picture', [ProfileController::class, 'getProfilePicture']);
        Route::post('/logout', [AuthController::class, 'logout']);

        //provider routes
        Route::post('/update_buisness', [ProviderController::class, 'updateBuisness']);
        Route::get('/get_incomming_bookings', [ProviderController::class, 'getIncommingBookings']);
        Route::get('/get_pending_bookings', [ProviderController::class, 'getPendingBookings']);
        Route::get('/get_completed_bookings', [ProviderController::class, 'getCompletedBookings']);
        Route::post('/delete_booking', [ProviderController::class, 'deleteBooking']);
        Route::post('/delete_media', [ProviderController::class, 'deleteMedia']);
        Route::post('/add_service', [ProviderController::class, 'addServices']);
        Route::post('/delete_service', [ProviderController::class, 'deleteService']);
        Route::post('/add_booking_to_pinding', [ProviderController::class, 'addBookingToPinding']);
        Route::post('/add_booking_to_completed', [ProviderController::class, 'addBookingToCompleted']);
        Route::post('/change_password', [AuthController::class, 'changePassword']);

        Route::post('add_fcm_token',[AuthController::class,'addFcmToken']);
         //chat
        Route::post('/send_message', [ChatController::class, 'sendMessage']);
        Route::get('/get_chat_list', [ChatController::class, 'getChatList']);
        Route::post('/get_messages', [ChatController::class, 'getMessages']);
    });
});

Route::post('/book_provider', [CustomerController::class, 'bookProvider']);
Route::get('/get_providers', [CustomerController::class, 'getProviders']);
Route::post('/show_provider_detail', [CustomerController::class, 'showProviderDetail']);
Route::post('/search_provider', [CustomerController::class, 'SearchProvider']);
Route::post('/add_review', [CustomerController::class, 'addReview']);
Route::post('/add_missed_apointment', [CustomerController::class, 'addMissedApointment']);
Route::post('/add_complaint', [CustomerController::class, 'addComplaint']);

Route::post('/search_by_location', [CustomerController::class, 'searchByLocation']);
Route::post('/show_provider_reviews', [CustomerController::class, 'showProviderReviews']);  //see all provider reviews
Route::get('/get_popular_categories', [CustomerController::class, 'getPopularCategories']);
Route::post('/get_traders_by_category', [CustomerController::class, 'getTradersByCategory']);
Route::get('/get_advertisment', [CustomerController::class, 'getAdvertisment']);
Route::get('/get_articals_questions', [HelpAndAdviceController::class, 'getArticalsAndQuestions']);
Route::get('/get_articals', [HelpAndAdviceController::class, 'getArticals']);
Route::post('/get_artical_detail', [HelpAndAdviceController::class, 'getArticalDeatail']);

Route::get('/get_questions', [HelpAndAdviceController::class, 'getQuestions']);
Route::post('/get_question_answer_detail', [HelpAndAdviceController::class, 'getQuestionAnswerDetail']);
//insurance

Route::post('/create_insurance', [InsuranceController::class, 'create']);  //see all provider reviews
Route::post('/contact_a_trader', [CustomerController::class, 'contactATrader']);
Route::get('/get_providers_titles', [CustomerController::class, 'getProvidersTitles']);

Route::get('/news',[NewsController::class,'getNews']);


