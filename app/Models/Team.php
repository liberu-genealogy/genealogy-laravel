<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TeamInvitation;
use App\Models\User;

class Team extends Model
{
    protected $fillable = [
        'id',
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function NewAddrs()
    {
        return $this->hasMany(NewAddr::class);
    }

    public function NewAuthors()
    {
        return $this->hasMany(NewAuthor::class);
    }

    public function NewChans()
    {
        return $this->hasMany(NewChan::class);
    }
    

    public function NewCitations()
    {
        return $this->hasMany(NewCitation::class);
    }
     

    public function NewDnaMatchings()
    {
        return $this->hasMany(NewDnaMatching::class);
    }
   

    public function NewDnas()
    {
        return $this->hasMany(NewDna::class);
    }

    public function NewFamilys()
    {
        return $this->hasMany(NewFamily::class);
    }


    public function NewFamilySlgs()
    {
        return $this->hasMany(NewFamilySlgs::class);
    }

    public function NewMediaObjects()
    {
        return $this->hasMany(NewMediaObject::class);
    }


    public function NewNotes()
    {
        return $this->hasMany(NewNote::class);
    }


    public function NewPersonAlias()
    {
        return $this->hasMany(NewPersonAlia::class);
    }

    public function NewPersonAncis()
    {
        return $this->hasMany(NewPersonAnci::class);
    }

    public function NewPersonAssos()
    {
        return $this->hasMany(NewPersonAsso::class);
    }


    public function newPeople()
    {
        return $this->hasMany(NewPerson::class);
    }



    public function NewSources()
    {
        return $this->hasMany(New_Source::class);
    }


    public function NewRepositories()
    {
        return $this->hasMany(NewRepository::class);
    }
}

