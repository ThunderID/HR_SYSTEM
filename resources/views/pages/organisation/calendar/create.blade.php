@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Kalender', 'route' => route('hr.calendars.index', ['org_id' => $data['id']]) ],
										['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.calendars.create', ['org_id' => $data['id']]) : route('hr.calendars.edit', ['id' => $id, 'org_id' => $data['id']]) )]
									]
		])
@stop

@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], ['name' => 'Kalender', 'route' => route('hr.calendars.index', ['org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'sidebar'				=>
										[
											'widget_title'		=> 'Pilih Organisasi :',								
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'pengaturan'		=> 'yes',
											'active_calendar'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.calendar.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'calendarlist'			=>
									[
										'form_url'			=> route('hr.calendars.store', ['id' => $id, 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.calendars.index', ['org_id' => $data['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite