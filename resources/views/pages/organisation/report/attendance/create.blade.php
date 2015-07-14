@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[	
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Laporan Kehadiran', 'route' => route('hr.report.attendances.index', ['org_id' => $data['id']]) ],
							['name' => $person['name'], 'route' => route('hr.report.attendances.show', ['person_id' => $person['id'], 'org_id' => $data['id'], 'start' => $start, 'end' => $end]) ],
							['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.report.attendances.create', ['org_id' => $data['id']]) : route('hr.report.attendances.edit', ['id' => $id, 'org_id' => $data['id']]) )]
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
											'laporan'			=> 'yes',
											'active_report_activities'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')
	@include('widgets.organisation.report.attendance.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'processlogslist'			=>
									[
										'form_url'			=> route('hr.report.attendances.store', array_merge(['id' => $id, 'org_id' => $data['id'], 'person_id' => $person['id']], Input::all())),
										'new'				=> (is_null($id) ? true : false),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.report.attendances.show', array_merge(['person_id' => $person['id'], 'org_id' => $data['id']], Input::all()))
									]
								]
	])
	

@overwrite

@section('content_footer')
@overwrite