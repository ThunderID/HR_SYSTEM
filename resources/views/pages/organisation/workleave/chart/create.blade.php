@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $workleave['name'], 'route' => route('hr.workleaves.show', ['id' => $workleave['id'], 'cal_id' => $workleave['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah Jabatan' : 'Ubah '), 'route' => (is_null($id) ? route('hr.workleave.charts.create', ['org_id' => $data['id'], 'cal_id' => $workleave['id']]) : route('hr.workleave.charts.edit', ['org_id' => $data['id'], 'cal_id' => $workleave['id'], 'id' => $id]) )]
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
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.workleave.chart.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'personworkleavelist'			=>
									[
										'form_url'			=> route('hr.workleave.charts.store', ['id' => $id, 'workleave_id' => $workleave['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id ,'withattributes' => ['workleave']],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.workleaves.index', ['org_id' => $data['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite