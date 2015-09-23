@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id'] ])], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Struktur Organisasi', 'route' => route('hr.branch.charts.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => $chart['name'], 'route' => route('hr.branch.charts.show', ['id' => $chart['id'], 'chart_id' => $chart['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Cuti Kerja', 'route' => route('hr.chart.workleaves.index', ['id' => $id, 'chart_id' => $chart['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
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
	@include('widgets.organisation.branch.chart.workleave.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'workleavelist'			=>
									[
										'form_url'			=> route('hr.chart.workleaves.store', ['id' => $id, 'chart_id' => $chart['id'], 'branch_id' => $branch['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'new'				=> (is_null($id) ? true : false),
										'search'			=> ['id' => $id ,'withattributes' => ['chart']],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.chart.workleaves.index', ['chart_id' => $chart['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite