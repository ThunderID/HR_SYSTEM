@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[	
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Laporan Aktivitas', 'route' => route('hr.report.attendances.index', ['org_id' => $data['id'], 'start' => $start, 'end' => $end]) ],
							['name' => $person['name'], 'route' => route('hr.attendance.persons.index', ['org_id' => $data['id'], 'person_id' => $person['id'], 'start' => $start, 'end' => $end]) ],
							['name' => date('d-m-Y', strtotime($ondate)), 'route' => route('hr.attendance.persons.show', ['person_id' => $person['id'], 'org_id' => $data['id'], 'person_id' => $person['id'], 'ondate' => $ondate]) ]
						]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> [
									'sidebar'				=>
									[
										'search'			=> [],
										'sort'				=> ['name' => 'asc'],
										'page'				=> 1,
										'per_page'			=> 100,
										'laporan'			=> 'yes',
										'active_report_attendances'	=> 'yes'
									]
								]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.report.attendance.person.log.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Laporan Aktivitas "'.$person['name'].'" <br/> Tanggal '.date('d-m-Y', strtotime($ondate)),
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'personlist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> ['id' => $person['id'], 'logsondate' => ['on' => [$ondate, date('Y-m-d',strtotime($ondate.' + 1 Day'))]]],
											'sort'				=> ['persons.name' => 'asc'],
											'page'				=> 1,
											'per_page'			=> 1,
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite