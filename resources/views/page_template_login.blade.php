<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HR System</title>
	<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-11 col-sm-6 col-md-6 col-xs-offset-1 col-sm-offset-6 col-md-offset-6">
				@yield('pages')				
			</div>
		</div>
	</div>
</body>
</html>