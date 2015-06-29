@section('area')
	@include('widgets.form.form_password', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'login' 				=> 
									[
										'widget_title'		=> 'Ubah Password',
										'form_url'			=> route('hr.password.post'),
									]
								]
	])		
@stop