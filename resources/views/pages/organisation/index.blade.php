@section('area')
	@include('widgets.organisation.select', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'organisationlist'	=> 
									[
										'form_url'			=> route('hr.organisations.show', 1),
										'search'			=> ['id' => Session::get('user.organisationids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 100,
									]
								]
	])	
@stop