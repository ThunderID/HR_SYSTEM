<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HR System</title>
	<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body style="background-color:#f5f5f5">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
				@yield('pages')					
			</div>
		</div>
	</div>
</body>
</html>