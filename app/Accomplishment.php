<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accomplishment extends Model
{
    protected $fillable = ['accomplishment_type', 'accomplishment_name', 'accomplishment_proficiency', 'linkedin_profile_id'];
}
