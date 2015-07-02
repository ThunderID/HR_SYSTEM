@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[	
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Laporan Aktivitas', 'route' => route('hr.report.attendances.index', ['org_id' => $data['id']]) ],
							['name' => $person['name'], 'route' => route('hr.attendance.persons.index', ['org_id' => $data['id'], 'person_id' => $person['id']]) ],
							['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.attendance.persons.create', ['org_id' => $data['id']]) : route('hr.attendance.persons.edit', ['id' => $id, 'org_id' => $data['id']]) )]
						]
	])
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
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'pengaturan'		=> 'yes',
											'active_attendance_report'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')
	@include('widgets.organisation.report.attendance.person.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'processlogslist'			=>
									[
										'form_url'			=> route('hr.attendance.persons.store', ['id' => $id, 'org_id' => $data['id'], 'person_id' => $person['id']]),
										'new'				=> (is_null($id) ? true : false),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.attendance.persons.index', array_merge(['org_id' => $data['id'], 'person_id' => $person['id']], Input::all()))
									]
								]
	])
	

@overwrite

@section('content_footer')
@overwrite