<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [ 'job_position', 'company_name', 'location', 'dates', 'linkedin_profile_id',];

    public function profile(){
        return $this->belongsTo('App/LinkedinProfile');
    }

}
