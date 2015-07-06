@section('area')
	@include('widgets.form.form_login', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'login' 				=> 
									[
										'widget_title'		=> 'HR System Login',
										'form_url'			=> route('hr.login.post'),
										'user_id'			=> 'username',
										'user_id_label'		=> 'Username'
									]
								]
	])		
@stop