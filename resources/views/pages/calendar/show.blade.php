@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Kalender', 'route' => route('hr.calendars.index', ['org_id' => $data['id']]) ],
										['name' => $calendar['name'], 'route' => route('hr.calendars.show', ['id' => $calendar['id'], 'calendar_id' => $calendar['id'],'org_id' => $data['id'] ])], 
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
											'widget_title'		=> 'Pilih Organisasi :',								
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.calendar.calendar', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'calendarlist'			=>
									[
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.calendars.index', ['org_id' => $data['id']])
									]
								]
	])
	
	{!! Form::open(array('route' => array('hr.calendar.schedules.delete', 0),'method' => 'POST')) !!}
		@include('widgets.modal.modal_create_schedule', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}

	{!! Form::open(array('route' => array('hr.calendar.schedules.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}

	<script type="text/javascript">
		var cal_link = "{!! route('hr.calendar.schedules.index',['org_id' => $data['id'],'cal_id' => $id]) !!}";
	</script>
@overwrite

@section('content_footer')
@overwrite