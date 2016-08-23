<?php

namespace App\Http\Controllers;

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
use App\TemporaryActivity;
use App\Screenshot;
use DB;
class UserController extends Controller
{

	function getScreenshots($id)
	{
		$computer = Computer::where('id', $id)->first();
		if($computer->user != Auth::user())
			return redirect(route('user.computers.get'));
		$screenshots = Screenshot::where('computer_id', $computer->id)->orderBy('created_at', 'desc')->paginate(20);

		return view('user.screenshots', compact('screenshots', 'computer'));
	}

	function postProfile(Request $request)
	{
		$this->validate($request, [
			'old_password' => 'required',
			'password' => 'required|confirmed|min:6',
			]);
		if(Hash::check($request->get('old_password'), Auth::user()->password))
		{
			Auth::user()->password = bcrypt($request->get('password'));
			Auth::user()->save();
			return redirect()->back();
		}
		else 
			return redirect()->back()->withErrors([
				'Old password doesnt match'
				]);
	}
	function getProfile()
	{
		return view('user.profile');
	}
	function getIndex()
	{
		if(Auth::user())
			return redirect(route('user.computers.get'));
		return view('index');
	}

	function getComputers()
	{

//return response()->make(base64_decode(''),200 ,array('Content-Type' => 'image/jpeg'));
		$computers = Computer::where('user_id', Auth::user()->id)->orderBy('nickname', 'asc')->get();
		return view('user.index',compact('computers'));
	}

	function getStatistics($id)
	{
		$computer = Computer::where('id', $id)->first();
		if($computer->user != Auth::user())
			return redirect(route('user.computers.get'));
		$topactivities = self::getTop($computer,7);

		return view('user.statistics', compact('computer', 'topactivities'));
	}

	function getComputer($id)
	{
		$computer = Computer::where('id', $id)->first();
		if($computer->user != Auth::user())
			return redirect(route('user.computers.get'));
		$topactivities = self::getTop($computer,5);
		if($computer->getStatus() == "Online")
		{
			$lastactivities = Activity::where('computer_id', $computer->id)->orderBy('start_time', 'desc')->skip(1)->take(10)->get();
			$last = Activity::where('computer_id', $computer->id)->orderBy('start_time', 'desc')->first();
			return view('user.computer', compact('computer', 'topactivities', 'lastactivities', 'last'));
		}
		else
		{
			$lastactivities = Activity::where('computer_id', $computer->id)->orderBy('start_time', 'desc')->take(10)->get();
			return view('user.computer', compact('computer', 'topactivities', 'lastactivities'));
		}
	}

	function getTop($calc, $number)
	{
		$activities =  DB::table('activities')
		->select('processname', DB::raw('SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) as time'))
		->groupBy('processname')
		->where('computer_id', $calc->id)
		->whereRaw('DATE(end_time) = DATE(NOW())')
		->orderByRaw('
			SUM(TIMESTAMPDIFF(SECOND,start_time,end_time)) DESC
			')->take($number)->get();



		return $activities;
	}

	function needsSeconds($a)
	{
		foreach($a as $w )
		{
			if($w->time > 0)
				return false;
		}
		return true;
	}
}