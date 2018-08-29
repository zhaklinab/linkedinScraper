<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'main_skill','linkedin_profile_id'];

    public const MAIN_SKILL = 1;
    public const DEFAULT_SKILL = 0;
}
