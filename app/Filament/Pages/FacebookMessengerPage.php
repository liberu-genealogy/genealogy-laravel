<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Message;

class FacebookMessengerPage extends Page
{
    public function mount()
    {
        $this->data([
            'user' => Auth::user(),
            'users' => User::where('id', '!=', Auth::id())->get(),
            'messages' => Message::where(function ($query) {
                $query->where('from_user_id', Auth::id())
                    ->where('to_user_id', Request::get('user_id'));
            })->orWhere(function ($query) {
                $query->where('from_user_id', Request::get('user_id'))
                    ->where('to_user_id', Auth::id());
            })->orderBy('created_at')->get(),
        ]);
    }

    public function sendMessage()
    {
        $validator = Validator::make(Request::all(), [
            'message' => 'required|string',
            'to_user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            // Handle validation errors
        }

        $message = new Message();
        $message->from_user_id = Auth::id();
        $message->to_user_id = Request::get('to_user_id');
        $message->message = Request::get('message');
        $message->save();

        return redirect()->back();
    }
}
