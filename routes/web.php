<?php

use App\User;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/mail', 'PagesController@sendmail')->name('mail');



// --------------------- CHAT
Route::view('/chat', 'pages.chat')->name('chat');
Route::get('/chat/{with_user_id}', 'MessagesController@chat_with')->name('chat_with');

Route::get('chat.private.fetch/{conversationId}', 'MessagesController@getMessagesForConvId')
            ->name('chat.private.fetch');

Route::post('chat.private.store/{conversationId}', 'MessagesController@sendMessage')
            ->name('chat.private.store');




// --------------------- CHAT



Route::get('/users', function() {
    $users = User::all();

    return view('users.index', compact('users'));
})->name('users');
