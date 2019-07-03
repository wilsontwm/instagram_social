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

Route::get('/', ['as' => 'welcome', 'uses' => 'InstagramController@welcome']);
Route::get('/about', function(){return view('about');});
Route::get('/privacy', function(){return view('privacy');});

// Authentication routes
Auth::routes();
Route::get('login/admin', ['as' => 'admin.login', 'uses' => 'Auth\LoginController@adminLogin']);

//Socialite routes
Route::get('login/instagram', ['as' => 'instagram.redirect', 'uses' => 'InstagramController@redirect']);
Route::get('redirect/{provider}', ['as' => 'social.redirect', 'uses' => 'Auth\SocialAuthController@redirect']);
Route::get('callback/{provider}', ['as' => 'social.callback', 'uses' => 'Auth\SocialAuthController@callback']);

Route::get('home', 'HomeController@index')->name('home');

Route::get('top/recent', ['as' => 'top.recent', 'uses' => 'InstagramController@topRecent']);

// Users routes
Route::post('search', ['as' => 'search', 'uses' => 'ProfileController@search']);
Route::get('profile/autocomplete', ['as' => 'profile.autocomplete', 'uses' => 'ProfileController@autocomplete']);
Route::get('profile/{username}', ['as' => 'profile', 'uses' => 'ProfileController@view']);

// Notes routes
Route::get('notes/{username}', ['as' => 'notes', 'uses' => 'NoteController@index']);
Route::post('notes/{user}/store', ['as' => 'notes.store', 'uses' => 'NoteController@store']);
Route::get('note/{note}', ['as' => 'notes.show', 'uses' => 'NoteController@show']);

// Authenticated routes
Route::group(['middleware' => 'auth'], function(){
    // Instagram routes
    Route::get('top', ['as' => 'top.view', 'uses' => 'InstagramController@topPosts']);
    Route::get('top/posts', ['as' => 'top.posts', 'uses' => 'InstagramController@getTopPosts']);

    // Notes routes
    Route::get('note/{note}/destroy', ['as' => 'notes.destroy', 'uses' => 'NoteController@destroy']);
    Route::get('note/{note}/togglePin', ['as' => 'notes.pin', 'uses' => 'NoteController@togglePin']);
    Route::post('note/{note}/comment/store', ['as' => 'notes.comments.store', 'uses' => 'NoteController@comment']);
    Route::post('note/{note}/comment/{comment}/delete', ['as' => 'notes.comments.delete', 'uses' => 'NoteController@removeComment']);

    // Notifications routes
    Route::get('notifications', ['as' => 'notifications.index', 'uses' => 'NotificationController@index']);
    Route::post('notifications/read', ['as' => 'notifications.read', 'uses' => 'NotificationController@read']);
    Route::post('notifications/delete', ['as' => 'notifications.delete', 'uses' => 'NotificationController@delete']);

    // Gifts routes
    Route::get('giftstore/{username}', ['as' => 'gifts', 'uses' => 'GiftController@index']);
    Route::post('giftstore/{user}/send', ['as' => 'gifts.send', 'uses' => 'GiftController@send']);
    Route::get('gifts/summary', ['as' => 'gifts.index', 'uses' => 'GiftController@myIndex']);
    Route::get('gifts/received', ['as' => 'gifts.received', 'uses' => 'GiftController@received']);
    Route::get('gifts/sent', ['as' => 'gifts.sent', 'uses' => 'GiftController@sent']);
    Route::get('gifts/{gift}', ['as' => 'gifts.show', 'uses' => 'GiftController@show']);
    Route::post('gifts/{gift}/comment/store', ['as' => 'gifts.comments.store', 'uses' => 'GiftController@comment']);
    Route::post('gifts/{gift}/comment/{comment}/delete', ['as' => 'gifts.comments.delete', 'uses' => 'GiftController@removeComment']);

    // Credit routes
    Route::get('credit', ['as' => 'credit', 'uses' => 'CreditController@show']);
    Route::post('paypal', ['as' => 'credit.paypal', 'uses' => 'CreditController@processPaypalPayment']);
    Route::get('paypal', ['as' => 'credit.paypal.status', 'uses' => 'CreditController@getPaypalPaymentStatus']);

    // Cashout request routes
    Route::get('cashout', ['as' => 'cashout.index', 'uses' => 'CashoutRequestController@index']);
    Route::get('cashout/create', ['as' => 'cashout.create', 'uses' => 'CashoutRequestController@create']);
    Route::post('cashout/store', ['as' => 'cashout.store', 'uses' => 'CashoutRequestController@store']);
    Route::get('cashout/amount', ['as' => 'cashout.amount', 'uses' => 'CashoutRequestController@getAmount']);
    Route::get('cashout/{cashout}', ['as' => 'cashout.show', 'uses' => 'CashoutRequestController@show']);
    Route::post('cashout/{cashout}/withdraw', ['as' => 'cashout.withdraw', 'uses' => 'CashoutRequestController@withdraw']);

    // Admin dashboard routes
    Route::group(['namespace' => 'Admin', 'middleware' => 'admin', 'prefix' => 'admin/', 'as' => 'admin.'], function() {
        Route::get('dashboard', ['as' => 'dashboard', function() {
            return redirect()->route('admin.users.index');
        }]);

        // Users management routes
        Route::get('users', ['as' => 'users.index', 'uses' => 'UserController@index']);
        Route::get('users/{user}/edit', ['as' => 'users.edit', 'uses' => 'UserController@edit']);
        Route::patch('users/{user}/update', ['as' => 'users.update', 'uses' => 'UserController@update']);

        // Gifts management routes
        Route::get('gifts', ['as' => 'gifts.index', 'uses' => 'GiftController@index']);
        Route::get('gifts/create', ['as' => 'gifts.create', 'uses' => 'GiftController@create']);
        Route::post('gifts/store', ['as' => 'gifts.store', 'uses' => 'GiftController@store']);
        Route::get('gifts/{gift}', ['as' => 'gifts.edit', 'uses' => 'GiftController@edit']);
        Route::get('gifts/{gift}/picture', ['as' => 'gifts.picture', 'uses' => 'GiftController@picture']);
        Route::patch('gifts/{gift}/update', ['as' => 'gifts.update', 'uses' => 'GiftController@update']);
        Route::post('gifts/{gift}/picture/store', ['as' => 'gifts.picture.store', 'uses' => 'GiftController@storePicture']);
        Route::delete('gifts/{gift}/picture/destroy', ['as' => 'gifts.picture.destroy', 'uses' => 'GiftController@destroyPicture']);

        // Cashout request management routes
        Route::get('cashout', ['as' => 'cashout.index', 'uses' => 'CashoutRequestController@index']);
        Route::get('cashout/{cashout}', ['as' => 'cashout.show', 'uses' => 'CashoutRequestController@show']);
        Route::patch('cashout/{cashout}/process', ['as' => 'cashout.process', 'uses' => 'CashoutRequestController@process']);
    });

});