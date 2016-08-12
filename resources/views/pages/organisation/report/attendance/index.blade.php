@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[	
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							(Input::has('start') ? 
							['name' => 'Laporan Kehadiran', 'route' => route('hr.report.attendances.index', ['org_id' => $data['id'], 'start' => $start, 'end' => $end]) ]
							:
							['name' => 'Laporan Kehadiran', 'route' => route('hr.report.attendances.index', ['org_id' => $data['id']]) ]
							)
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
										'active_report_attendances' => 'yes'
									]
								]
	])
@overwrite

@section('content_filter')
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> route('hr.report.attendances.index', ['org_id' => $data['id'], 'start' => $start, 'end' => $end, 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
	])
@overwrite

@section('content_body')	
	@if(Input::has('start') && Input::has('withschedule'))
		@include('widgets.organisation.report.attendance.global.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Laporan Kehadiran "'.date('d-m-Y',strtotime($start)).' s/d '.date('d-m-Y',strtotime($end)).'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'personlist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> array_merge(['processlogattendancesondate' => ['on' => [$start, $end]], 'chartnotadmin' => true], (isset($filtered['search']) ? $filtered['search'] : [])),
											'sort'				=> ['persons.name' => 'asc'],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 50,
										]
									]
	])	
	@elseif(Input::has('start'))
		@include('widgets.organisation.report.attendance.table', [
			'widget_template'		=> 'panel',
			'widget_title'			=> 'Laporan Kehadiran "'.date('d-m-Y',strtotime($start)).' s/d '.date('d-m-Y',strtotime($end)).'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
			'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
			'widget_body_class'		=> '',
			'widget_options'		=> 	[
											'personlist'			=>
											[
												'organisation_id'	=> $data['id'],
												'search'			=> array_merge(['globalattendance' => ['on' => [$start, $end]], 'chartnotadmin' => true], (isset($filtered['search']) ? $filtered['search'] : [])),
												'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['persons.name' => 'asc']),
												'page'				=> (Input::has('page') ? Input::get('page') : 1),
												'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
												'per_page'			=> 50,
											]
										]
		])
	@else
		@include('widgets.common.filter.date', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Tanggal Laporan Kehadiran',
		'widget_options'		=> [
									'form_url'	=> route('hr.report.attendances.index', ['org_id' => $data['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
		])
	@endif
@overwrite

@section('content_footer')
@overwrite