@section('area')
	@include('widgets.form.form_login', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['widget_title'		=> 'HR System Login',
								'form_url'			=> route('hr.postlogin'),
								'user_id'			=> 'email',
								'user_id_label'		=> 'Email'
								]
	])		
@stop