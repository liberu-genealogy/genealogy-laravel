<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::to(env('CONTACT_EMAIL'))->send(new ContactMail($validatedData));

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
    public function sendFacebookMessage(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
        ]);
    
        $page = new FacebookMessengerPage();
        $page->mount();
    
        $request->merge(['user_id' => $validatedData['to_user_id']]);
        Request::replace($request->all());
    
        $page->sendMessage();
    
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
    {
        $validatedData = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::to(env('CONTACT_EMAIL'))->send(new ContactMail($validatedData));

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
