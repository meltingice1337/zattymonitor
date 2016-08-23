@extends('user.app')
@section('title')Tracked computer complex details @stop

@section('content')
<link rel="stylesheet" href="{{asset('css/daterangepicker.css')}}" />
<link rel="stylesheet" href="{{asset('css/select2.min.css')}}" />
<div class="col-md-10">
<div class="well" style="margin-top:5px;">
	<div class="computerstatus col-md-12 col-xs-12"><h1 class="pcnicknameandstat">{{$computer->nickname}}</h1>
		<p class="pcsname">{{$computer->name}})</p>
	</div>
	<div class="datepicker" style="padding:15px">
		<h4>Select dates</h4>
		<input style="width:300px" type="text" name="daterange"  />
	</div>
	<div class="applicationselector">
		<p><h4>Select application</h4>Want to track a certain application?</p>
		<select name="apps[]" multiple="multiple" data-tags="true" data-placeholder="Select an option" data-allow-clear="true"></select>
		<button class="btn btn-default changenicknamebutton" id="load">Load</button>
	</div>
	<div class="col-md-12 chartcontent" style="height:400px; padding:5px" >
		<h3 style="margin-left:25px">Most used applications </h3>
		<canvas id="TodayChart" width="400" height="400" ></canvas>
	</div>
</div>
</div>
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/daterangepicker.js')}}"></script>
<script src="{{asset('js/chart.js')}}"></script>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script type="text/javascript">


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


	$('#load').on('click',function(){
		var items= $('select').val();  
		$('#TodayChart').remove();
		$('h3').after('<canvas id="TodayChart" width="400" height="400" ></canvas>');

		var ctx = document.getElementById("TodayChart");
		var start_time = moment($('input[name="daterange"]').val().split(' - ')[0]).format('YYYY-MM-DD H:mm');
		var end_time =  moment($('input[name="daterange"]').val().split(' - ')[1]).format('YYYY-MM-DD H:mm');
		$.get("{{route('api.statistics', $computer->id)}}?start_time=" + start_time + "&end_time=" + end_time + '&q=' + JSON.stringify(items), function(data) {
			var t = JSON.parse(data);
			var labels = [], data = [];
			$.each(t, function(index,value){
				labels.push(value.processname);
				data.push(value.time);
			});
			var myChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						backgroundColor: ["#FF6384",
						"#000",
						"#FFCE56",
						"#E7E9ED",
						"#012400",
						"#000"],

						label: 'Seconds Spent ',
						data: data
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false
				}
			});

		}).fail(function() {
			alert("eroare");
		});     
	});
$("select").select2({
	tags: true,
	multiple: true,
	tokenSeparators: [',', ' '],
	ajax: {
		url: '{{route("api.apps",$computer->id)}}',
		dataType: "json",
		type: "GET",

		data: function (params) {

			var queryParameters = {
				q: params.term
			}
			return queryParameters;
		},
		processResults: function (data) {
			return {
				results: $.map(data, function (item) {
					return {
						text: item.processname,
						id: item.processname,
						value: item.processname
					}
				})
			};
		}
	}
});

var ctx = document.getElementById("TodayChart");
$('input[name="daterange"]').daterangepicker({
	timePicker: true,
	timePicker24Hour: true,
	timePickerIncrement: 1,
	locale: {
		format: 'MM/DD/YYYY H:mm '
	}
}, function(start, end, label) {

});
$('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
	$('#TodayChart').remove();
	$('h3').after('<canvas id="TodayChart" width="400" height="400" ></canvas>');
	var ctx = document.getElementById("TodayChart");

	var start_time = picker.startDate.format('YYYY-MM-DD H:mm');
	var end_time =  picker.endDate.format('YYYY-MM-DD H:mm');
	$.get("{{route('api.statistics', $computer->id)}}?start_time=" + start_time + "&end_time=" + end_time, function(data) {
		var t = JSON.parse(data);
		var labels = [], data = [];
		$.each(t, function(index,value){
			labels.push(value.processname);
			data.push(value.time);
		});
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [{
					backgroundColor: ["#FF6384",
					"#000",
					"#FFCE56",
					"#E7E9ED",
					"#012400",
					"#000"],

					label: 'Seconds Spent ',
					data: data
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false
			}
		});

	}).fail(function() {
		alert("eroare");
	});
});

</script>
@stop