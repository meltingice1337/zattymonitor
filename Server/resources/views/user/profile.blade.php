@extends('user.app')

@section('title')Profile @stop

@section('content')
<div id="profilecontent"class="col-md-10">
	<div class="col-md-4 col-xs-12">
		<div id="" class="myprofilecontainer col-md-12 col-xs-12">
			<div class="panel panel-default ">
				<div class="panel-heading">
					<p class="">Account Information</p>
				</div>
				<div class="panel-body" style="">
					<p style="font-size:15px"> {{Auth::user()->email}} </p>
					<p style="font-size:15px"> Member since: {{Auth::user()->created_at}} </p>
				</div>
			</div>
		</div>
		<div id="" class=" myprofilecontainer col-md-12 col-xs-12">
			<div class="panel panel-default ">
				<div class="panel-heading">
					<p class="">Change your password</p>
				</div>
				<div class="panel-body" style="text-align:left">
					<form action="{{route('user.profile.post')}}" method="POST">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<p style="font-size:15px">Old password</p><input type="text" class="form-control" name="old_password">
						<p style="font-size:15px">New password</p><input type="text" class="form-control" name="password">
						<p style="font-size:15px">Password confirmation</p><input type="text" class="form-control" name="password_confrimation">
						<br>
						<button class="btn btn-default proceedbutton" style="text-align:center">Proceed</button>
						<br>
						@if(count($errors)>0)
						<div class="alert alert-danger" style="margin-bottom:50px" role="alert"><strong>Incorrect! </strong>Some errors have been made</div>
						@endif
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="" class=" myprofilecontainer col-md-8 col-xs-12 ">
		<div class="panel panel-default ">
			<div class="panel-heading">
				<p class="">Tracked computers</p>
			</div>
			<div class="panel-body" style="color:grey">
				@foreach(Auth::user()->computers as $computer)
				<p style="font-size:17px;color:black">{{$computer->nickname}} ({{$computer->name}}) since {{$computer->created_at}}</p>
				@endforeach
			</div>
		</div>
	</div>
</div>
@stop