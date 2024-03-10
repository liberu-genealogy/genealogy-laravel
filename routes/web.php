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
    return view('layouts.home');


Route::get('/fan-chart', 'App\Http\Controllers\FanChartController@show');

Route::post('/send-invitation', 'TeamInvitationController@sendInvitation')->name('send.invitation');
Route::post('/accept-invitation/{token}', 'TeamInvitationController@acceptInvitation')->name('accept.invitation');

});

Route::prefix('filament')->group(function () {
    Route::get('/pedigree-chart', \App\Http\Livewire\PedigreeChart::class)->name('pedigree-chart');
});
Route::get('/descendant-chart', 'DescendantChartController@index')->name('descendant-chart');
