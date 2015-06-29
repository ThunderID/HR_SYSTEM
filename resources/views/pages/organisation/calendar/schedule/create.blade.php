@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $calendar['name'], 'route' => route('hr.calendars.show', ['id' => $calendar['id'], 'cal_id' => $calendar['id'],'org_id' => $data['id'] ])], 
						['name' => 'Jadwal', 'route' => route('hr.calendar.schedules.index', ['id' => $calendar['id'], 'cal_id' => $calendar['id'],'org_id' => $data['id'] ])], 
					]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
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
											'active_calendar'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.schedule.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'schedulelist'			=>
									[
										'form_url'			=> route('hr.calendar.schedules.store', ['id' => $id, 'cal_id' => $calendar['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'new'				=> (is_null($id) ? true : false),
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.calendars.show', [$calendar['id'], 'org_id' => $data['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite