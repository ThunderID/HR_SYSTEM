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
											'identifier'		=> 1,
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])
@overwrite

@section('content_body')
	@include('widgets.schedule.table', [
		'widget_template'		=> 'panel',
		'widget_options'		=> 	[
										'schedulelist'			=>
										[
											'identifier'		=> 1,
											'calendar_id'		=> $calendar['id'],
											'search'			=> ['calendarid' => $calendar['id']],
											'sort'				=> ['on' => 'desc'],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite