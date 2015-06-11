@section('area')
	@include('widgets.form.form_choice_org', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',
								'form_url'			=> route('hr.postlogin'),
								'org_id'			=> 'organisasi',
								'org_id_label'		=> 'Organisasi'
								]
	])	
@stop