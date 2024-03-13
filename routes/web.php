<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 *
 * This file is responsible for registering all the web routes for the application.
 * Routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 */

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
    return view('layouts.home');



Route::post('/send-invitation', 'TeamInvitationController@sendInvitation')->name('send.invitation');
Route::post('/accept-invitation/{token}', 'TeamInvitationController@acceptInvitation')->name('accept.invitation');



});

use App\Http\Livewire\PedigreeChart;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;




Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');
Route::get('/terms-and-conditions', function () {
    return view('pages.termsandconditions');
})->name('terms.and.conditions');

Route::get('/contact', function () { return view('contact'); });
Route::post('/contact/send', 'App\Http\Controllers\ContactController@sendEmail');

Route::get('/manage-subscription', [\App\Filament\Pages\ManageSubscription::class, 'mount'])->middleware('auth')->name('manage.subscription');


