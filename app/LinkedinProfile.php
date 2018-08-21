<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkedinProfile extends Model
{
    protected $fillable = [ 'name', 'email', 'description', 'location',];

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function accomplishment()
    {
        return $this->hasMany(Accomplishment::class);
    }

    public function skill()
    {
        return $this->hasMany(Skill::class);
    }
}
