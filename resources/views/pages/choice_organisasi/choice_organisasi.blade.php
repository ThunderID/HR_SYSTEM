@section('area')
	@include('widgets.form.form_choice_org', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',
								'form_url'			=> route('hr.postlogin'),
								'user_id'			=> 'email',
								'user_id_label'		=> 'Email'
								]
	])	
@stop