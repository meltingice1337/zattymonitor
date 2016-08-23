@extends('user.app')

@section('title'){{$computer->nickname}}'s Screenshots @stop

@section('content')
<div class="col-md-10">
<div class="well computerstatus col-md-12 col-xs-12"><h1 class="pcnicknameandstat">{{$computer->nickname}}</h1>
	<p class="pcsname"> ({{$computer->name}})</p>
	@if($computer->getStatus() == 'Online')
	<h1 class="onlinestat">Online</h1>
	@else
	<h1 class="offlinestat">Offline</h1>
	@endif
	<button href="" class="btn btn-default screenshotpage" id="ss">Take Screenshot</button>
	<h4 style="font-style:italic; margin-left:15px; color:grey">You can take up to 10 screnshots a day!</h4>
	@if($computer->screenshot)
	<p style="float:left; margin-left:15px; font-size:25px;">Waiting for screenshot</p>
	@else
	<p id="gallery" style="float:left; margin-left:15px; font-size:25px;">Screenshots gallery</p>
	@endif
</div>

</br>
</div>
<div class="col-md-8 col-xs-12 ">
</div>

<div class="col-md-12 col-xs-12 col-sm-6 photolibrary">
	<div id="lightgallery"  >
		@foreach($screenshots as $s)
		<a href="{{route('api.image', ['id' => $computer->id, 'number'=> $s->id])}}" data-sub-html="<p>{{$s->time}}</p>">
			<div class="col-md-3">
				<p style="text-align:center">{{$s->time}}</p>
				<img  class="thumbnailimages img-responsive"src="{{route('api.image', ['id' => $computer->id, 'number'=> $s->id])}}" />
			</div>
		</a>
		@endforeach
	</div>
</div>
</div>
<link rel="stylesheet" href="{{asset('css/lightgallery.min.css')}}">
<script src="{{asset('js/jquery.min.js')}}"></script>
<script src="{{asset('js/lg-thumbnail.min.js')}}"></script>
<script src="{{asset('js/lg-fullscreen.min.js')}}"></script>
<script src="{{asset('js/lightgallery.min.js')}}"></script>
<script>
	$("#lightgallery").lightGallery();

	$('#ss').on('click', function(e){
		e.preventDefault();
		$.get("{{route('api.screenshot', $computer->id)}}?send=true", function(data) {
			waitScreenshot();
			if(data)
			{
				$('h4').after('<p style="float:left; margin-left:15px; font-size:25px;">Waiting for screenshot</p>');
				$('#gallery').remove();
			}
			else {
				alert(data);
				location.reload();
			}
		}).fail(function(e) {
		});
	});

	function waitScreenshot(){
		$.get("{{route('api.screenshot', $computer->id)}}", function(data) {
			if(data == "false")
				location.reload();
			else
				setTimeout(waitScreenshot,1000);
		}).fail(function(e) {
				setTimeout(waitScreenshot,1000);
			
		});;

	}
</script>
@stop