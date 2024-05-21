<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
});

Route::post('/send-invitation', 'TeamInvitationController@sendInvitation')->name('send.invitation');
Route::post('/accept-invitation/{token}', 'TeamInvitationController@acceptInvitation')->name('accept.invitation');

// Admin Panel Routes
Route::group(['prefix' => 'admin', 'middleware' => ['web']], function () {
    // Jetstream Authentication Routes
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

        // Jetstream Registration Routes
        Route::get('/register', [AdminRegistrationController::class, 'showRegistrationForm'])->name('admin.register');
        Route::post('/register', [AdminRegistrationController::class, 'register'])->name('admin.register.submit');
    });

    Route::group(['middleware' => ['auth:admin']], function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
        // Other admin panel routes...
    });
});

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');
Route::get('/terms-and-conditions', function () {
    return view('pages.termsandconditions');
})->name('terms.and.conditions');

Route::get('/about', function () {
    return view('pages.aboutus');
})->name('about');

Route::get('/contact', function () { return view('contact'); });
Route::post('/contact/send', 'App\Http\Controllers\ContactController@sendEmail');
