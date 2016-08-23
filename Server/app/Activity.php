<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    protected $table = 'activities';

    protected $fillable = [
    'processname','windowname', 'start_time', 'end_time','icon',
    ];

    public function computer()
    {
        return $this->belongsTo('App\Computer');
    }
    public function temp_activities()
    {
    	return $this->hasMany('App\TemporaryActivity');
    }
}

