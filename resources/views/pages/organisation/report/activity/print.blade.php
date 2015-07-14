@section('area')	
		@include('widgets.organisation.report.attendance.table', [
			'widget_template'		=> 'panel_no_title',
			'widget_title'			=> 'Laporan Aktivitas "'.date('d-m-Y',strtotime($start)).' - '.date('d-m-Y',strtotime($end)).'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
			'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
			'widget_body_class'		=> '',
			'widget_options'		=> 	[
											'personlist'			=>
											[
												'organisation_id'	=> $data['id'],
												'search'			=> ['globalattendance' => array_merge(['organisationid' => $data['id'], 'on' => [$start, $end]], (isset($filtered['search']) ? $filtered['search'] : []))],
												'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['persons.name' => 'asc']),
												'page'				=> (Input::has('page') ? Input::get('page') : 1),
												'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
												'per_page'			=> 100,
												'route_create'		=> route('hr.calendars.create', ['org_id' => $data['id']])
											]
										]
		])

		
@overwrite
