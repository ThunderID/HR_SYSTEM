@section('area')
	@include('widgets.common.form_name', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['form_url'			=> route('hr.organisations.store', ['id' => $id]),
								'identifier'		=> 1,
								'search'			=> ['id' => $id],
								'sort'				=> [],
								'page'				=> 1,
								'per_page'			=> 1,
								'route_back'		=> route('hr.organisations.show', ['id' => $id, 'org_id' => $id])
								]
	])	
@stop