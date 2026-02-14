<?php

use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AIMatchController;
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

Route::get('/', fn() => view('home'));

Route::post('/send-invitation', [TeamInvitationController::class, 'sendInvitation'])->name('send.invitation');
Route::post('/accept-invitation/{token}', [TeamInvitationController::class, 'acceptInvitation'])->name('accept.invitation');

// Route::redirect('/register', '/admin/register')->name('register');

Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');
Route::get('/terms-and-conditions', fn() => view('pages.termsandconditions'))->name('terms.and.conditions');

Route::get('/about', fn() => view('pages.aboutus'))->name('about');

Route::get('/contact', fn() => view('contact'));
Route::post('/contact/send', [ContactController::class, 'sendEmail'])->name('contact.send');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/gamification', \App\Http\Livewire\GamificationDashboard::class)->name('gamification');
    Route::get('/transcriptions', \App\Livewire\DocumentTranscriptionComponent::class)->name('transcriptions');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/ai/matches/{suggestion}/confirm', [AIMatchController::class, 'confirm'])->name('ai.matches.confirm');
    Route::post('/ai/matches/{suggestion}/reject', [AIMatchController::class, 'reject'])->name('ai.matches.reject');
});
