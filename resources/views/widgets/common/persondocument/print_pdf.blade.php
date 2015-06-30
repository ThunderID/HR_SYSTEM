<html>
	<head>
		{{-- <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'> --}}
		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #000;
				display: block;
				font-weight: 300;
				font-family: 'Lato';
			}
			.container {
				text-align: center;
				display: block;
				vertical-align: middle;
			}
			.content {
				text-align: center;
				display: inline-block;
				width: 80%;
				padding: 40px;
			}
			.title {
				font-size: 72px;
				margin-bottom: 40px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<h1> {{ ($document['document']['name']) }}</h1>
				@if(isset($template) && !is_null($template) && $template!='')
					{!!$template!!}
				@else
					<div class="clearfix">&nbsp;</div>
					@foreach($document['details'] as $key => $value)
						<div class="row">
							<div class="col-sm-3">
								{!!ucwords($value['template']['field'])!!}
							</div>
							<div class="col-sm-9">
								{!!($value['text'] ? ucwords($value['text']) : ucwords($value['numeric']))!!}
							</div>
						</div>
						<div class="clearfix">&nbsp;</div>
					@endforeach
					<div class="clearfix">&nbsp;</div>
				@endif
			</div>
		</div>
	</body>
</html>
