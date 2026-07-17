<?php

declare(strict_types=1);

use App\Http\Controllers\AIMatchController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FanChartController;
use App\Http\Controllers\PedigreeChartController;
use App\Livewire\DocumentTranscriptionComponent;
use App\Livewire\FamilyTreeBuilder;
use App\Livewire\GamificationDashboard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\Redirector;
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

Route::get('/', fn (): Factory|\Illuminate\Contracts\View\View => view('home'));

Route::get('/register', fn (): Redirector|\Illuminate\Http\RedirectResponse => redirect('/app/register'))->name('register');
Route::get('/login', fn (): Redirector|\Illuminate\Http\RedirectResponse => redirect('/app/login'))->name('login');

Route::get('/privacy', fn (): Factory|\Illuminate\Contracts\View\View => view('pages.privacy'))->name('privacy');
Route::get('/terms-and-conditions', fn (): Factory|\Illuminate\Contracts\View\View => view('pages.termsandconditions'))->name('terms.and.conditions');

Route::get('/about', fn (): Factory|\Illuminate\Contracts\View\View => view('pages.aboutus'))->name('about');

Route::get('/subscription', fn (): Factory|\Illuminate\Contracts\View\View => view('pages.subscription'))->name('subscription');

Route::get('/contact', fn (): Factory|\Illuminate\Contracts\View\View => view('contact'));
// throttle: this endpoint drives mail from an unauthenticated public form. With
// no limit it is an open relay pointed at whoever contact.to is.
Route::post('/contact/send', [ContactController::class, 'sendEmail'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    Route::get('/dashboard', fn (): Factory|\Illuminate\Contracts\View\View => view('dashboard'))->name('dashboard');
    Route::get('/gamification', GamificationDashboard::class)->name('gamification');
    Route::get('/transcriptions', DocumentTranscriptionComponent::class)->name('transcriptions');
    Route::get('/fan-chart', [FanChartController::class, 'show'])->name('fan-chart');
    Route::get('/pedigree-chart', [PedigreeChartController::class, 'show'])->name('pedigree-chart');
    Route::get('/family-tree', FamilyTreeBuilder::class)->name('family-tree');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::post('/ai/matches/{suggestion}/confirm', [AIMatchController::class, 'confirm'])->name('ai.matches.confirm');
    Route::post('/ai/matches/{suggestion}/reject', [AIMatchController::class, 'reject'])->name('ai.matches.reject');
});

// Stripe webhook endpoint used by Laravel Cashier
Route::post('/stripe/webhook', '\\Laravel\\Cashier\\Http\\Controllers\\WebhookController@handleWebhook');
