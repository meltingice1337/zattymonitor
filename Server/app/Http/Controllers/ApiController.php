<?php

namespace App\Http\Controllers;

use Hash;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\View;
use Redirect;
use App\Classes\Security;
use App\Computer;
use App\Activity;
use App\Screenshot;
use App\TemporaryActivity;
use DB;

class ApiController extends Controller
{
    function getImage($id, $number)
    {
        $computer = Computer::where('id', $id)->first();
        if($computer->user != Auth::user())
            return 'error';
        $screenshot = Screenshot::where('id', $number)->first();
        if(!$screenshot)
            return 'error';

        return response()->make($screenshot->img ,200 ,array('Content-Type' => 'image/jpeg'));
    }

    function getScreenshot(Request $request, $id)
    {
        $computer = Computer::where('id', $id)->first();
        if($computer->user != Auth::user())
            return 'error';
        if($computer->maxScreenshots())
            return 'You arrived at your maximum number of screenshots';

        if($request->send)
        {
            $computer->screenshot = 1;
            $computer->save();
            return 1;
        }
        return $computer->screenshot ? "true": "false";

    }
    function getApps(REquest $request , $id)
    {
       $computer = Computer::where('id', $id)->first();
        if($computer->user != Auth::user())
            return 'error';
        if($request->get('q'))
         $shit =  (DB::table('activities')->select('processname', 'id')->where('computer_id', $computer->id)->where('processname', 'LIKE', '%'.$request->get('q').'%')->groupBy('processname')->get());
     else return json_encode(array());
     return json_encode($shit);

 }
 function getStatistics(Request $request, $id)
 {
    $computer = Computer::where('id', $id)->first();
    if($computer->user != Auth::user())
        return 'error';
    if($request->get('start_time') && $request->get('end_time') && !$request->get('q'))
    {
        $activities =  DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) as time'))
        ->groupBy('processname')
        ->whereRaw('start_time >= "'.$request->get('start_time').'"')
        ->whereRaw('end_time <= "'.$request->get('end_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->take(6)->get();

        $first = DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,"'.$request->get('start_time').'",end_time)) as time'))
        ->groupBy('processname')
        ->whereRaw('start_time < "'.$request->get('start_time').'"')
        ->whereRaw('end_time > "'.$request->get('start_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->first();

        $last = DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,start_time,"'.$request->get('end_time').'")) as time'))
        ->groupBy('processname')
        ->whereRaw('start_time < "'.$request->get('end_time').'"')
        ->whereRaw('end_time > "'.$request->get('end_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->first();
        foreach($activities as $a)
        {
            if(isset($first))
            {
                if($a->processname == $first->processname)
                {
                    $a->time += $first->time;
                    $first->set = true;
                }
            }

            if(isset($last))
            {
                if($a->processname == $last->processname)
                {
                    $a->time += $last->time;
                    $last->set = true;
                }
            }

            if(isset($first))
            {
                if(!isset($first->set))
                    array_push($activities, $first);
            }

            if(isset($last))
            {
                if(!isset($last->set))
                    array_push($activities, $last);
            }
            usort($activities, function($a, $b){
                return $a->time < $b->time;
            });
            if(count($activities) == 8)
            {
                array_pop($activities);
                array_pop($activities);
            }
            else  if(count($activities) == 7)
                array_pop($activities);

            return json_encode($activities);
        }

        return json_encode(array());
    }
    else if ($request->get('start_time') && $request->get('end_time') && $request->get('q'))
    {
        $q = json_decode($request->get('q'));
        $activities =  DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) as time'))
        ->groupBy('processname')
        ->whereIn('processname', $q)
        ->whereRaw('start_time >= "'.$request->get('start_time').'"')
        ->whereRaw('end_time <= "'.$request->get('end_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->take(6)->get();
        $first = DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,"'.$request->get('start_time').'",end_time)) as time'))
        ->groupBy('processname')
        ->whereIn('processname', $q)
        ->whereRaw('start_time < "'.$request->get('start_time').'"')
        ->whereRaw('end_time > "'.$request->get('start_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->first();

        $last = DB::table('activities')
        ->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,start_time,"'.$request->get('end_time').'")) as time'))
        ->groupBy('processname')
        ->whereIn('processname', $q)
        ->whereRaw('start_time < "'.$request->get('end_time').'"')
        ->whereRaw('end_time > "'.$request->get('end_time').'"')
        ->orderByRaw('
            SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
            ')->first();
        foreach($activities as $a)
        {
            if(isset($first))
            {
                if($a->processname == $first->processname)
                {
                    $a->time += $first->time;
                    $first->set = true;
                }
            }

            if(isset($last))
            {
                if($a->processname == $last->processname)
                {
                    $a->time += $last->time;
                    $last->set = true;
                }
            }

            if(isset($first))
            {
                if(!isset($first->set))
                    array_push($activities, $first);
            }

            if(isset($last))
            {
                if(!isset($last->set))
                    array_push($activities, $last);
            }
            usort($activities, function($a, $b){
                return $a->time < $b->time;
            });
            if(count($activities) == 8)
            {
                array_pop($activities);
                array_pop($activities);
            }
            else  if(count($activities) == 7)
                array_pop($activities);


        }
        return json_encode($activities);
    }
}

function getNickname(Request $request, $id)
{
    $computer = Computer::where('id', $id)->first();
    if($computer->user != Auth::user())
        return 'error';
    if(!$request->get('nickname'))
        return 'error';
    if($request->get('nickname') == '')
        return 'error';

    $computer->nickname = $request->get('nickname');
    $computer->save();
    return 'ok';
}

function getStatus($id)
{
    $computer = Computer::where('id', $id)->first();
    if($computer->user != Auth::user())
        return 'Error';
    if($computer->getStatus() != 'Online')
        return TemporaryActivity::where('computer_id', $computer->id)->orderBy('created_at', 'desc')->first()->created_at;
    else return 'Online';
}

function getLastActivity($id)
{
    $computer = Computer::where('id', $id)->first();
    if($computer->user != Auth::user()) 
        return '';
    if($computer->getStatus() != 'Online') 
        return "";
    return json_encode(Activity::where('computer_id', $computer->id)->orderBy('created_at', 'desc')->take(11)->get());
}

private $enc_key,$auth_key;

function getLogin(Request $request)
{

    if(!$request->get('password') || !$request->get('email'))
    {
        return response()->json([
            'message' => 'You must enter a password and an email'
            ],400 );
    }

    $user = User::where('email', $request->get('email'))->first();

    if (!$user)
    {
        return response()->json([
            'message' => 'Email not found in the database'
            ],400);
    }
    if (Hash::check($request->get('password'), $user->password)){

        $computer = Computer::create([
            "enc_key" => Security::generateKey(),
            "auth_key" => Security::generateKey(),
            "send_key" => self::generateSendKey()
            ]);
        $user->computers()->save($computer);
        return response()->json([
            'message'  => 'Success',
            "enc_key"  => $computer->enc_key,
            "auth_key" => $computer->auth_key,
            "send_key" => $computer->send_key
            ],200);
    }
    else{
        return response()->json([
            'message' => 'Incorect password'
            ],401 );
    }
}

function postSend(Request $request)
{
    if(!$request->get('key') || !$request->get('data'))
    {
        return response()->json([
            'message' => 'Invalid request'
            ],400 );
    }
    $computer = Computer::where('send_key', $request->get('key'))->first();
    if(!$computer)
    {

        return response()->json([
            'message' => 'Invalid key'
            ],400 );
    }

    $this->enc_key = $computer->enc_key;
    $this->auth_key = $computer->auth_key;
    $cipherData = self::DecodeData($request->get('data'));
    if(!Security::VerifyMessage($cipherData, $this->auth_key))
    {
        return response()->json([
            'message' => 'Could not authenticate message'
            ],400 );
    }

    $deciphredData = Security::Decrypt($cipherData, $this->enc_key);
    if(!$deciphredData)
    {
        return response()->json([
            'message' => 'Could not decrypt message'
            ],400 );
    }
    $command = json_decode($deciphredData);
    $commandData = json_decode($command->data);

    switch ($command->type) {
        case 'info':
        $computer->name = $commandData[0];
        $computer->nickname = $commandData[0];
        $computer->os = $commandData[1];
        $computer->save();
        return self::EncryptResponse([
            'message' => 'ok'
            ],200 );
        break;

        case 'log':
        $logs = $commandData;
        if($computer->screenshot)
        {
            $m = "screenshot";
            $computer->screenshot = 0;
            $computer->save();
        }
        else $m = "";

        foreach($logs as $log)
        {
            self::InsertLog($computer, $log);
        }
        return self::EncryptResponse([
            'message' => $m
            ],200 );
        break;

        case 'screenshot':

        $computer->screenshot = 0;
        $computer->save();
        $ss = Screenshot::create([
            'time' => $commandData->time, 
            'img' => base64_decode($commandData->img),
            'user_id' => $computer->user->id
            ]);
        $computer->screenshots()->save($ss);

        return self::EncryptResponse([
            'message' => 'ok'
            ],200 );
        break;

        default:
        return self::EncryptResponse([
            'message' => 'This type does not exist'
            ],400 );
        break;
    }
}
function InsertLog($computer, $log)
{
    $act = Activity::where('computer_id', $computer->id)->orderBy('id', 'desc')->first();
    if(!$act)
    {
       $new_act = Activity::create([
        'processname' => $log->processName,
        'windowname' => $log->windowName,
        'start_time' => date('Y-m-d H:i:s', strtotime($log->time))
        ]);
       $new_temp_act = TemporaryActivity::create([
        'processname' => $log->processName,
        'windowname' => $log->windowName,
        'time' => $log->time
        ]);
       $computer->temp_activities()->save($new_temp_act);
       $computer->activities()->save($new_act);
       $new_act->temp_activities()->save($new_temp_act);
   }
   else if($act->windowname != $log->windowName)
   {
    if(!$act->end_time)
    {
        $old_act = TemporaryActivity::where('computer_id', $computer->id)->orderBy('id', 'desc')->first();
        $act->end_time = date('Y-m-d H:i:s', strtotime($old_act->time));
        $act->save();
        $act->temp_activities()->delete();
    }
    $new_act = Activity::create([
        'processname' => $log->processName,
        'windowname' => $log->windowName,
        'start_time' => date('Y-m-d H:i:s', strtotime($log->time))
        ]);
    $new_temp_act = TemporaryActivity::create([
        'processname' => $log->processName,
        'windowname' => $log->windowName,
        'time' => $log->time
        ]);
    $computer->temp_activities()->save($new_temp_act);
    $computer->activities()->save($new_act);

    $new_act->temp_activities()->save($new_temp_act);

}
else if ($act->windowname == $log->windowName && !$act->end_time){
  $new_temp_act = TemporaryActivity::create([
    'processname' => $log->processName,
    'windowname' => $log->windowName,
    'time' => $log->time
    ]);
  $computer->temp_activities()->save($new_temp_act);
  $act->temp_activities()->save($new_temp_act);
}
}

function generateSendKey(){
    $key = Security::generateKey();
    $computer = Computer::where('send_key', $key)->first();
    while($computer)
    {
        $key = Security::generateKey();
        $computer = Computer::where('send_key', $key)->first();
    }
    return $key;
}
function DecodeData($data)
{
    $data = urldecode($data);
    $data = str_replace(" ", "+",$data);
    return $data;
}
function EncryptResponse($array, $status)
{
    $content = Security::EncryptAndSign(json_encode($array),$this->enc_key, $this->auth_key);
    return response($content,$status)->header('Content-Type', "application/javascript");;
}
}