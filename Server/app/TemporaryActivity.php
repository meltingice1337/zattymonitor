<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemporaryActivity extends Model
{

    protected $table = 'temp_activities';

    protected $fillable = [
    'processname','windowname','time','icon',
    ];

    public function computer()
    {
        return $this->belongsTo('App\Computer');
    }
    public function activity()
    {
    	return $this->belongsTo('App\Activity');
    }
}

