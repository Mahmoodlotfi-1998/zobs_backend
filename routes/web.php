<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneOtpController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/test_view',function (){
    return view('dashboard');
});

Route::get("/api/payment" , [ App\Http\Controllers\PaymentController::class , 'Payment'] );

Route :: post("/api/phone_otp" , [PhoneOtpController::class , 'Manage_Request'] );

Route :: post("/api/register" , [UserController::class , 'Register'] );
    Route :: post("/api/dashboard" , [UserController::class , 'Update'] );
Route :: post("/api/get_user_info" , [UserController::class , 'GetUserInfo'] );
Route :: post("/api/subscription" , [UserController::class , 'GetSubscription'] );
Route :: post("/api/get_subscription" , [UserController::class , 'GetUserSubscription'] );

Route :: post("/api/category" , [HomeController::class , 'getCategory'] );
Route :: post("/api/version" , [HomeController::class , 'getVersion'] );
Route :: get("/api/index" , [HomeController::class , 'getIndexPage'] );

Route :: post("/api/add_service" , [\App\Http\Controllers\LMServicesController::class , 'AddServices'] );
Route :: post("/api/price_off_code" , [\App\Http\Controllers\LMServicesController::class , 'CheckCode'] );
Route :: post("/api/get_all_services" , [\App\Http\Controllers\LMServicesController::class , 'GetAllServices'] );
Route :: post("/api/get_services" , [\App\Http\Controllers\LMServicesController::class , 'GetServices'] );

Route :: post("/api/add_ticket" , [\App\Http\Controllers\LMTicketsController::class , 'AddTicket'] );
Route :: post("/api/get_tickets" , [\App\Http\Controllers\LMTicketsController::class , 'GetAllTickets'] );
Route :: post("/api/get_tickets_web" , [\App\Http\Controllers\LMTicketsController::class , 'GetAllTicketsOrderWeb'] );

Route:: get('/test' ,[\App\Http\Controllers\PaymentController::class,'test_jdf']);

Route:: get('/pages/policy' ,[HomeController::class,'policyPage']);
Route:: get('/pages/about-us' ,[HomeController::class,'aboutPage']);
Route:: get('/pages/law' ,[HomeController::class,'lawPage']);

//panel api
Route:: get('/admin' ,[\App\Http\Controllers\PanelController::class,'Admin']);
Route:: get('/dashboard' ,[\App\Http\Controllers\PanelController::class,'Dashboard']);
Route:: get('/setting' ,[\App\Http\Controllers\PanelController::class,'Setting']);
Route:: get('/users' ,[\App\Http\Controllers\PanelController::class,'Users']);
Route:: get('/category' ,[\App\Http\Controllers\PanelController::class,'Category']);
Route:: get('/dcategory.php' ,[\App\Http\Controllers\PanelController::class,'DCategory']);
Route:: get('/services' ,[\App\Http\Controllers\PanelController::class,'Services']);
Route:: get('/payment' ,[\App\Http\Controllers\PanelController::class,'Payment']);
Route:: get('/subscription' ,[\App\Http\Controllers\PanelController::class,'Subscription']);
Route:: get('/ticket' ,[\App\Http\Controllers\PanelController::class,'Ticket']);
Route:: get('/chat.php' ,[\App\Http\Controllers\PanelController::class,'Chat']);
Route:: get('/discount' ,[\App\Http\Controllers\PanelController::class,'Discount']);
Route:: post('/api/otp' ,[\App\Http\Controllers\PanelController::class,'PhoneOtp']);
Route:: post('/api/panel' ,[\App\Http\Controllers\PanelController::class,'PanelControl']);
