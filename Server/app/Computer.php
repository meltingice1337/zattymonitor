<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class Computer extends Model
{

    protected $table = 'computers';

    protected $fillable = [
    'name', 'nickname', 'send_key', 'enc_key', 'auth_key', 'os',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function screenshots()
    {
        return $this->hasMany('App\Screenshot');
    }
    public function activities()
    {
    	return $this->hasMany('App\Activity');
    }
    public function temp_activities()
    {
        return $this->hasMany('App\TemporaryActivity');
    }
    public function maxScreenshots()
    {
        $activities =  DB::table('screenshots')
        ->select('*')
        ->whereRaw('DATE(created_at) = DATE(NOW())')
        ->count();

        return $activities < 10 ? 0 : 1;
    }
    public function getStatus()
    {
        $act = TemporaryActivity::where('computer_id', $this->id)->orderBy('created_at', 'desc')->first();
        if(!$act)
            return 0;
        $diff = time() - strtotime($act->created_at);
        if( $diff > 2)
            return 'Last seen '.self::secondsToTime($diff).' ago';
        else 
            return "Online";

    }

    public function takeScreenshot()
    {
        $this->screenshot = 1;
        $this->save();
    }

    function secondsToTime($seconds) {
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
        if($seconds < 60)
            return $dtF->diff($dtT)->format('%s seconds');
        else if($seconds < 3600)
            return $dtF->diff($dtT)->format('%i minutes');
        else if($seconds < 86400)
            return $dtF->diff($dtT)->format('%h hours and %i minutes');
        else
            return $dtF->diff($dtT)->format('%a days, %h hours and %i minutes');
    }
}

