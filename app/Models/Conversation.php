<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Conversation extends Model
{
    use HasFactory;

    #[\Override]
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

    /**
     * Both participants. userOne/userTwo are belongsTo, so they resolve to single
     * User models — merge() is a Collection method and User has none, so this threw
     * BadMethodCallException for anyone who called it. Nothing did yet.
     *
     * @return Collection<int, User>
     */
    public function users(): Collection
    {
        return collect([$this->userOne, $this->userTwo])->filter()->values();
    }
}
