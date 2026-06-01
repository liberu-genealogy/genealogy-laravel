<?php

declare(strict_types=1);

use App\Http\Controllers\AIMatchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FanChartController;
use App\Http\Controllers\PedigreeChartController;
use App\Http\Controllers\TeamInvitationController;
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

Route::get('/', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('home'));

Route::post('/send-invitation', [TeamInvitationController::class, 'sendInvitation'])->name('send.invitation');
Route::post('/accept-invitation/{token}', [TeamInvitationController::class, 'acceptInvitation'])->name('accept.invitation');

Route::get('/register', fn (): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => redirect('/app/register'))->name('register');
Route::get('/login', fn (): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse => redirect('/app/login'))->name('login');

Route::get('/privacy', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('pages.privacy'))->name('privacy');
Route::get('/terms-and-conditions', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('pages.termsandconditions'))->name('terms.and.conditions');

Route::get('/about', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('pages.aboutus'))->name('about');

Route::get('/subscription', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('pages.subscription'))->name('subscription');

Route::get('/contact', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('contact'));
Route::post('/contact/send', [ContactController::class, 'sendEmail'])->name('contact.send');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    Route::get('/dashboard', fn (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View => view('dashboard'))->name('dashboard');
    Route::get('/gamification', \App\Livewire\GamificationDashboard::class)->name('gamification');
    Route::get('/transcriptions', \App\Livewire\DocumentTranscriptionComponent::class)->name('transcriptions');
    Route::get('/fan-chart', [FanChartController::class, 'show'])->name('fan-chart');
    Route::get('/pedigree-chart', [PedigreeChartController::class, 'show'])->name('pedigree-chart');
    Route::get('/family-tree', \App\Livewire\FamilyTreeBuilder::class)->name('family-tree');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::post('/ai/matches/{suggestion}/confirm', [AIMatchController::class, 'confirm'])->name('ai.matches.confirm');
    Route::post('/ai/matches/{suggestion}/reject', [AIMatchController::class, 'reject'])->name('ai.matches.reject');
});

// Stripe webhook endpoint used by Laravel Cashier
Route::post('/stripe/webhook', '\\Laravel\\Cashier\\Http\\Controllers\\WebhookController@handleWebhook');
