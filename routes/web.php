<?php

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

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/index', function () {
    return view('index');
});


Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/api/v1/farmers/{page?}', 'FarmerController@index');
Route::post('/api/v1/farmers', 'FarmerController@store');
Route::post('/api/v1/farmers/{id}', 'FarmerController@update');
Route::delete('/api/v1/farmers/{id}', 'FarmerController@destroy');

Route::get('/api/v1/duplicate_farmers/{page?}', 'FarmerController@duplicate');

Route::get('/api/v1/subscriptions/{page?}/{startDate?}/{endDate?}', 'SubscriptionController@index');
Route::get('/api/v1/payment_history/{page?}/{phone?}', 'SubscriptionController@payment_history');
Route::get('/api/v1/unsubscribed_farmers/{page?}', 'SubscriptionController@unsubscribed_farmers');

Route::get('/api/v1/outbox/{page?}/{startDate?}/{endDate?}', 'SmsController@index');
Route::post('/api/v1/send', 'SmsController@send');
Route::get('/api/v1/scheduledsms/{page?}/{startDate?}/{endDate?}', 'SmsController@scheduledsms');

Route::get('/api/v1/districts', 'SmsController@districts');
Route::get('/api/v1/regions', 'SmsController@regions');
Route::get('/api/v1/crops', 'SmsController@crops');

//dashboard routes
Route::get('/api/v1/farmersSummary', 'DashboardController@farmersSummary');
Route::get('/api/v1/subscriptionSummary', 'DashboardController@subscriptionSummary');
Route::get('/api/v1/paymentsSummary', 'DashboardController@paymentsSummary');


