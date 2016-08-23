@extends('user.app')

@section('title')Tracked computer information @stop

@section('content')

<div class="col-md-10">
<div class="well computerstatus col-md-12 col-xs-12">
	<h1 class="pcnicknameandstat" id="nickname">{{$computer->nickname}}</h1>
	<p class="pcsname"> ({{$computer->name}})</p>
	@if(App\TemporaryActivity::where('computer_id', $computer->id)->orderBy('created_at', 'desc')->first())
	<h1 class="onlinestat" id="online" style="display:none;">Online</h1>
	<h1 class="offlinestat" id="offline">Offline</h1>
	@endif
	<div class="changenicknamebuttonandinput">
		<a href="" class="btn btn-default changenicknamebutton">Change Nickname</a>
		<div class="form-group inputnickname">
			<input type="text" class="form-control" id="c_nickname">
		</div>
	</div>
	<div style="padding-top:20px">
		<br>
		<p class="pclastseen"style="font-size:30px " id="time">Last seen at {{App\TemporaryActivity::where('computer_id', $computer->id)->orderBy('created_at', 'desc')->first()->created_at }}</p>
	</br>
</div>
</div>

<div class="col-md-12 col-xs-12">
	<a href="{{route('user.computer.screenshots.get',$computer->id)}}" class="btn btn-default screenshot">Screenshots</a>
	<h4 style="font-style:italic">Wanna see what's really happening? Screenshot the live session right now!</h4>
</div>
<div class="col-md-4 col-xs-12 col-sm-6 livesesion">
	<div class="panel panel-default currentactivitypanel">
		<div class="panel-heading">
			<p class="currentactivity">Current activity: <span id="current_a">@if(isset($last)) {{$last->windowname}} @endif</span> </p>
		</div>
		<div class="panel-body">
			<p class="recentactivities">Today's recent activities: </p>
			<div id="activityblock">
				@foreach($lastactivities as $a)
				<p class="activities">{{$a->windowname}}</p>
				@endforeach
			</div>
		</div>
	</div>
</div>
<div class="col-md-8 col-xs-12 col-sm-6 statistics">
	<a href="{{route('user.computer.statistics.get', $computer->id)}}">
		<div class="panel panel-default ">
			<div class="panel-heading">
				<p class="currentactivity">Today's Statistics. Click too see all time history.</p>
			</div>
			<div class="panel-body">
				<div class="col-md-12" style="height:400px">
					<canvas id="TodayChart" width="400" height="400" ></canvas>
				</div>
			</div>
		</div>
	</a>
</div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/chart.js')}}"></script>
<script>

	var ctx = document.getElementById("TodayChart");
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: [@foreach ($topactivities as $a) "{{$a->processname}}", @endforeach],
			datasets: [{
				backgroundColor: ["#FF6384",
				"#000",
				"#FFCE56",
				"#E7E9ED",
				"#012400",
				"#000"],

				label: '@if(isset($topactivities[0]->m)) Minutes @else Seconds @endif Spend ',
				data: [@foreach ($topactivities as $a) "{{$a->time}}", @endforeach]
			}]
		},
		options: {
			legendTemplate : '<ul>'
			+'<% for (var i=0; i<datasets.length; i++) { %>'
			+'<li>'
			+'<span style=\"background-color:<%=datasets[i].lineColor%>\"></span>'
			+'<% if (datasets[i].label) { %><%= datasets[i].label %><% } %>'
			+'</li>'
			+'<% } %>'
			+'</ul>'
			,
			responsive: true,
			maintainAspectRatio: false
		}
	});
	var legend = myChart.generateLegend();
	$('#TodayChart').append(legend);
	
	$('.changenicknamebutton').on('click', function(e){
		e.preventDefault();
		var nickname = $('#c_nickname').val();
		if(nickname != '')
		{
			$.get("{{route('api.nickname', $computer->id)}}?nickname=" + nickname, function(data) {
				if(data!='error')
					$('#nickname').text(nickname);
			});
		}
	});

	getLastActivity();
	function getLastActivity(){
		$.get("{{route('api.lastactivity', $computer->id)}}", function(data) {
			if(data != "")
			{
				var t = JSON.parse(data);
				$('#activityblock').empty();
				$('#current_a').text(t[0].windowname);
				$.each(t, function(index,value){
					if(index > 0)
					{
						$('#activityblock').append('<p class="activities">' + value.windowname + '</p>')
					}
				});
			}
		}).fail(function() {

		});
		setTimeout(getLastActivity,1000);
	}

	getComputerStatus();
	function getComputerStatus(){
		$.get("{{route('api.status', $computer->id)}}", function(data) {
			if(data == 'Online')
			{
				$('#offline').hide();
				$('#time').hide();
				$('#online').show();
			}
			else{
				$('#current_a').text('');
				$('#offline').show();
				$('#time').show();
				$('#time').text('Last seen at ' + data);
				$('#online').hide();
			}
		}).fail(function() {

		});
		setTimeout(getComputerStatus,1000);
	}

</script>
@stop