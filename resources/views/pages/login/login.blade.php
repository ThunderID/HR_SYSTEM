@section('area')
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3 col-md-offset-4">
				@include('widgets.form.form_login', [
					'widget_template'	=> 'panel',
					'widget_options'	=> ['widget_title'		=> 'HR System Login',
											'form_url'			=> route('hr.postlogin'),
											'user_id'			=> 'email',
											'user_id_label'		=> 'Email'
											]
				])
			</div>
		</div>
	</div>
@stop