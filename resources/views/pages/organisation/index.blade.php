@section('area')
	@include('widgets.organisation.select', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',
								'form_url'			=> route('hr.organisations.show', 1),
								'identifier'		=> 1,
								'search'			=> [],
								'sort'				=> [],
								'page'				=> 1,
								'per_page'			=> 12,
								]
	])	
@stop