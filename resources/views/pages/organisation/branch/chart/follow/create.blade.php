@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => $chart['name'], 'route' => route('hr.branch.charts.show', ['id' => $chart['id'], 'chart_id' => $chart['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Kalender Kerja', 'route' => route('hr.chart.calendars.index', ['id' => $id, 'chart_id' => $chart['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah' : 'Ubah '), 'route' => (is_null($id) ? route('hr.branch.charts.create', ['org_id' => $data['id'], 'branch_id' => $branch['id']]) : route('hr.branch.charts.edit', ['org_id' => $data['id'], 'branch_id' => $branch['id'], 'id' => $id]) )]
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
										'sidebar'					=> 
										[
											'search'				=> ['withattributes' => 'branches'],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'active_chart_branch'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.branch.chart.follow.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'followlist'			=>
									[
										'form_url'			=> route('hr.chart.calendars.store', ['id' => $id, 'chart_id' => $chart['id'], 'branch_id' => $branch['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id ,'withattributes' => ['chart']],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.chart.calendars.index', ['chart_id' => $chart['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite