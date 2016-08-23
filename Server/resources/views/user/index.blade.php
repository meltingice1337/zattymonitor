@extends('user.app')

@section('title')Dashboard @stop

@section('content')
<div class="pclist col-md-10">
	@foreach($computers as $computer)
	<div class="col-md-3 col-xs-12 col-sm-6 trackedpc">
		<a href="{{route('user.computer.get', $computer->id)}}">
			<div class="panel panel-default pcpanel">
				<div class="panel-heading"><img src="{{asset('images/pc.png')}}" class="img-pc"/></div>
				<div class="panel-body">
				<p class="pcname">{{$computer->nickname}}</p>
					<p class="pcnickname">({{$computer->name}})</p>
					<p class="pclastseen2">{{$computer->getStatus()}}</p>
				</div>
			</div>

		</a>
	</div>
	
	@endforeach
</div>
@stop