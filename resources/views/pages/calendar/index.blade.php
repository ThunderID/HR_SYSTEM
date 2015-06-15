@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], ['name' => 'Kalender', 'route' => route('hr.calendars.index', ['org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> [
									'widget_title'		=> 'Pilih Organisasi :',								
									'identifier'		=> 1,
									'organisation_id'	=> 1,
									'search'			=> [],
									'sort'				=> ['name' => 'asc'],
									'page'				=> 1,
									'per_page'			=> 100,
								]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	

	@include('widgets.calendar.table', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Kalender Index',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'organisation_id'	=> $data['id'],
										'identifier'		=> 1,
										'search'			=> [],
										'sort'				=> ['name' => 'asc'],
										'page'				=> (Input::has('page') ? Input::get('page') : 1),
										'per_page'			=> 12,
									]
	])

@overwrite

@section('content_footer')
@overwrite