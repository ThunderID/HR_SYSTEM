<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HR System</title>	
	<link rel="stylesheet" href="{{ elixir('css/app.css') }}">
	{!! HTML::style('plugins/font-awesome/css/font-awesome.min.css') !!}	
	<!-- <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'> -->
</head>
<body>	
	<div id="wrapper">
		<!-- Navigation -->		
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<!-- topbar -->
		    @yield('nav_topbar', '[nav_topbar]')   
		
		    <div class="navbar-default sidebar" role="navigation">
		        <div class="sidebar-nav navbar-collapse">
		        	<!-- sidebar -->
		            @yield('nav_sidebar', '[nav_sidebar]')
		        </div>		        
		    </div>		    
		</nav>
		<div id="page-wrapper">
			@yield('content_filter', '[content_filter]')

			@if($errors->count())
				<div class="clearfix">&nbsp;</div>
				@include('widgets.common.alert', ['errors' => $errors])
			@endif

			<div class="row">	
				<div class="col-xs-12 col-sm-12 col-md-12">
					@yield('content_body', '[content_body]')
				</div>
			</div>

			@yield('content_footer', '[content_footer]')
		</div>
	</div>
	{!! HTML::script('plugins/jquery/jquery-2.1.4.min.js') !!}
	{!! HTML::script('plugins/bootstrap/bootstrap.min.js') !!}
	{!! HTML::script('plugins/metisMenu/metisMenu.min.js') !!}
	@include('plugins.select2')
	@include('plugins/toggle')
	@include('plugins/modal')
	@include('plugins.checkbox')
	@include('plugins.inputmask')
	@include('plugins.calendar')
	<script>
		$('#side-menu').metisMenu();
	</script>
</body>
</html>