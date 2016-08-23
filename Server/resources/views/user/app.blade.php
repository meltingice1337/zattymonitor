
<!DOCTYPE html>
<html>

<head>

  <title>User Panel</title>
  <meta name="description" content="find out what yo mama is doing">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/main.css')}}">
  <link rel="stylesheet" href="{{asset('css/hover-min.css')}}">
  <link rel="icon" type="image/png" href="{{asset('favicon2.png')}}">
  @yield('header')
</head>
<body>
  <div class="fixpart header2">
    <img src="{{asset('images/logo3.png')}}"/>
    <div class="dropdown dropdownuser ">
      <button class="btn btn-primary dropdown-toggle userbutton" type="button" data-toggle="dropdown"><i class="fa fa-user"></i> {{Auth::user()->email}}
       <span class="caret"></span></button>
       <ul class="dropdown-menu">
         <li><a href="{{route('logout.get')}}">Log out</a></li>
       </ul>
     </div>
   </div>
   <div class="col-md-12" style="border-bottom:1px solid #4cb5f5">
        <p class="profilename">@yield('title')</p>
        </div>
        
    <div class="menu col-md-2" >
    
      <ul id="menu"class=" myprofilemenu sidebar-nav">
      
        <p>
        <li class="hvr hvr-float" role="presentation" ><a href="{{route('user.computers.get')}}" id="dashboardbutton">Dashboard</a></li>
        </p>
        <p>
        <li class="hvr hvr-float"role="presentation"><a href="{{route('user.profile.get')}}" id="profilebutton">Profile</a></li>
        </p>
      </ul>
    </div>
  @yield('content')
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/jquery.sticky.js')}}"></script>
  <script src="{{asset('js/bootstrap.min.js')}}"></script>
  <script>
  
    if($(window).width() >= 991){
      $("#menu").sticky({topSpacing:15});
  }
  else{
    $("#menu").unstick();
}
$( window ).resize(function() {
    if($(window).width() >= 991){
      $("#menu").sticky({topSpacing:15});
  }
  else{
    $("#menu").unstick();
}
});

</script>
</body>
</html>
