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

/**
 * Display the descendant chart.
 *
 * @return \Illuminate\Contracts\View\View
 */
Route::get('/descendant-chart', 'DescendantChartController@index')->name('descendant-chart');
Route::post('/accept-invitation/{token}', 'TeamInvitationController@acceptInvitation')->name('accept.invitation');

});
