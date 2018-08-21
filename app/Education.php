<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = ['institution_name', 'degree', 'dates','linkedin_profile_id',];
}
