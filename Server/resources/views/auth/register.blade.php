
<!DOCTYPE html>
<html>

<head>

  <title>Join Zatty Monitor</title>
  <meta name="description" content="find out what yo mama is doing">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/main.cs')}}s">
  <link rel="stylesheet" href="{{asset('css/menu.css')}}">
  <link rel="icon" type="image/png" href="{{asset('favicon2.png')}}">

</head>
<body>
  <div class="fixpart header2">
    <img src="{{asset('images/logo3.png')}}" class="fixpic"/>
  </div>
  <form action="{{route('register.post')}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="col-md-offset-4 col-md-4 col-xs-12 registerinputs ">
    <h2 style="text-align:center">Welcome Zatty Monitor</h2>
      <p style="text-align:center">
Get in-the-moment updates on the things that interest you. And watch events unfold, in real time, from every angle.</p>
      <div class="form-group">
        <label for="usr">Email:</label>
        <input type="text" class="form-control" id="usr" name="email">
      </div>
      <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" id="pwd" name="password">
      </div>
      <div class="form-group">
        <label for="pwd">Password confirmation:</label>
        <input type="password" class="form-control" id="pwd" name="password_confirmation">
      </div>
      <div class="checkbox">
        <label><input type="checkbox" value=""> I agree to the Zatty Monitor </label> <button type="button" data-toggle="modal" data-target="#myModal" class="privacyterms"><strong>Terms</strong> and <strong>Privacy</strong>.</button>
      </div>
      <button href="" class="btn btn-default enterbutton">Register</button>
      @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> Au fost gasite cateva erori !<br><br>
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>
  </form>

</body>
</html>
