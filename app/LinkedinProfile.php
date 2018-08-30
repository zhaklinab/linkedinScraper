<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedinProfile extends Model
{
    protected $fillable = [ 'name', 'email', 'description', 'location', 'current_position','profile_url'];

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function accomplishments()
    {
        return $this->hasMany(Accomplishment::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
}
