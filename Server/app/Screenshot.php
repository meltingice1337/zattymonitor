<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{

    protected $table = 'screenshots';

    protected $fillable = [
    'time','img', 'user_id'
    ];

    public function computer()
    {
        return $this->belongsTo('App\Computer');
    }
    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

