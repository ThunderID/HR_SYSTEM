@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' 	=> 	[	
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Laporan Kehadiran', 'route' => route('hr.report.wages.index', ['org_id' => $data['id']]) ]
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
										'active_wages_report' => 'yes'
									]
								]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@if(Input::has('start'))
		@include('widgets.organisation.report.wage.table', [
			'widget_template'		=> 'panel',
			'widget_title'			=> 'Laporan Kehadiran "'.date('d-m-Y',strtotime($start)).' - '.date('d-m-Y',strtotime($end)).'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
			'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
			'widget_body_class'		=> '',
			'widget_options'		=> 	[
											'personlist'			=>
											[
												'organisation_id'	=> $data['id'],
												'search'			=> ['globalwage' => ['organisationid' => $data['id'], 'on' => [$start, $end]]],
												'sort'				=> ['persons.name' => 'asc'],
												'page'				=> 1,
												'per_page'			=> 100,
											]
										]
		])
	@else
		@include('widgets.common.filter.date', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Tanggal Laporan Kehadiran',
		'widget_options'		=> [
									'form_url'	=> route('hr.report.wages.index', ['org_id' => $data['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
		])
	@endif
@overwrite

@section('content_footer')
@overwrite