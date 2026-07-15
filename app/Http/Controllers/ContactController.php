<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // config(), not env(): once `php artisan config:cache` runs — as it does
        // in production — env() returns null outside config files, and this
        // silently became Mail::to(null).
        $recipient = config('contact.to');

        if (blank($recipient)) {
            Log::error('Contact form submitted but contact.to is not configured; message dropped.');

            return back()
                ->withInput()
                ->with('error', 'Sorry — the contact form is not configured right now. Please try again later.');
        }

        Mail::to($recipient)->send(new ContactMail($validatedData));

        return back()->with('success', 'Thanks — your message is on its way. We\'ll reply to the address you gave.');
    }
}
