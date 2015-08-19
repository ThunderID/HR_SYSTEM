@extends('widget_templates.'. (isset($widget_template) ? $widget_template : 'plain_no_title'))

	@section('widget_body')
		@foreach (['alert_success', 'alert_warning', 'alert_danger', 'alert_info'] as $alert)
			@if (Session::has($alert))
				<div class="clearfix">&nbsp;</div>
				<div class='alert {{str_replace("alert_", "alert-", $alert)}} mt-10'>
					@if (is_array(Session::get($alert)))
						@foreach (Session::get($alert) as $message)
							<div class="row">
								<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
									@if ($alert == 'alert_danger')
										<i class="fa fa-exclamation-circle" style="font-size:40px"></i>
									@elseif ($alert == 'alert_warning')
										<i class="fa fa-warning" style="font-size:40px"></i>
									@elseif ($alert == 'alert_info')
										<i class="fa fa-info-circle" style="font-size:40px"></i>
									@else
										<i class="fa fa-check-circle" style="font-size:40px"></i>
									@endif
								</div>
								<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
									<p>{{$message}}</p>
								</div>
							</div>
						@endforeach
					@else
						<div class="row">
							<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
								@if (Session::get($alert) == 'alert_danger')
									<i class="fa fa-exclamation-circle" style="font-size:40px"></i>
								@elseif (Session::get($alert) == 'alert_warning')
									<i class="fa fa-warning" style="font-size:40px"></i>
								@elseif (Session::get($alert) == 'alert_info')
									<i class="fa fa-info-circle" style="font-size:40px"></i>
								@else
									<i class="fa fa-check-circle" style="font-size:40px"></i>
								@endif
							</div>
							<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
								<p>{{ Session::get($alert) }}</p>
							</div>
						</div>
					@endif
				</div>
			@endif
		@endforeach

		@if (isset($errors) && $errors->count())
			<div class="clearfix">&nbsp;</div>
			<div class='alert alert-danger mt-10'>
				<div class="row">
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
						<i class="fa fa-exclamation-circle" style="font-size:40px"></i>
					</div>
					<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
						@foreach ($errors->all('<p>:message</p>') as $error)
							{!! $error !!}
						@endforeach
					</div>
				</div>
			</div>
		@endif

		<div class="clearfix">&nbsp;</div>
		<div class='alert alert-info mt-10 alert_batch hide'>
			<div class="row">
				<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
					<i class="fa fa-info-circle" style="font-size:40px"></i>
				</div>
				<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
					<p class="message_batch">Batch cuti sedang diproses..</p>
					<div class="progress mt-10">
						<div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="500" style="width: 50%;">
							100 / 500 Person
						</div>
				
					</div>
				</div>
			</div>
		</div>
	@overwrite
