<html>

<head>

  <title>Join Zatty Monitor</title>
  <meta name="description" content="find out what yo mama is doing">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/main.cs')}}s">
  <link rel="stylesheet" href="{{asset('css/menu.css')}}">
  <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  <link rel="icon" type="image/png" href="{{asset('favicon2.png')}}">

</head>
<body>
  <div class="fixpart header2">
    <img src="{{asset('images/logo3.png')}}" class="fixpic"/>
  </div>
  <form action="{{route('login.post')}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="col-md-offset-4 col-md-4 col-xs-12 registerinputs ">
      <div class="form-group">
      <h2 style="text-align:center">Log into Zatty Monitor</h2>
      <p style="text-align:center">Sign in to see your live activity data.</p>

        <label for="usr">Email:</label>
        <input type="text" class="form-control" id="usr" name="email">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password">
      </div>
      <button href="" class="btn btn-default enterbutton">Sign in</button>
      @if(count($errors)>0)
      <div class="alert alert-danger" style="margin-bottom:50px" role="alert"><strong>Incorrect! </strong>Incorrect password or username. </div>
      @endif
    </div>
  </form>
</body>
</html>
