<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HR System</title>
	<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
</head>
<body style="background-color:#f5f5f5">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3" style="margin-top:12%">
				@yield('area', ['area'])
			</div>
		</div>
	</div>
</body>
</html>