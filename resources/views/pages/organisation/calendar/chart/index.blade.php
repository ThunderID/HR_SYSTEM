@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 		=> 	[
									['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
									['name' => 'Kalender', 'route' => route('hr.calendars.index', ['org_id' => $data['id']]) ],
									['name' => $calendar['name'], 'route' => route('hr.calendars.show', ['id' => $calendar['id'], 'org_id' => $data['id']]) ],
									['name' => 'Kalender Kerja', 'route' => route('hr.calendar.charts.index', ['org_id' => $data['id'], 'cal_id' => $calendar['id']]) ]
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
									]
								]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.calendar.chart.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Jabatan - Kalender Kerja "'.$calendar['name'].'" '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
				'widget_options'		=> 	[
												'followlist'		=>
												[
													'search'			=> ['calendarid' => $calendar['id'], 'withattributes' => ['chart', 'chart.branch']],
													'sort'				=> [],
													'page'				=> (Input::has('page') ? Input::get('page') : 1),
													'per_page'			=> 12
												]
											]
			])

	{!! Form::open(array('route' => array('hr.calendars.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite