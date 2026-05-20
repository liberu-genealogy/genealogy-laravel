<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['user_one', 'user_two', 'status'];

    // public function user()
    // {
    //     return $this->hasOne(User::class);
    // }
    public function message()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function users()
    {
        return $this->userOne->merge($this->userTwo());
    }
}
