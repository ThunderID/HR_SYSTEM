@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id'] ])], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Struktur Organisasi', 'route' => route('hr.branch.charts.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => $chart['name'], 'route' => route('hr.branch.charts.show', ['id' => $chart['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Cuti Kerja', 'route' => route('hr.chart.workleaves.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
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

@section('content_body')

			@include('widgets.organisation.branch.chart.workleave.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Cuti Kerja "'.$chart['name'].'" '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
				'widget_options'		=> 	[
												'workleavelist'		=>
												[
													'search'			=> ['chartid' => $chart['id'], 'withattributes' => ['workleave']],
													'sort'				=> [],
													'page'				=> (Input::has('page') ? Input::get('page') : 1),
													'per_page'			=> 12
												]
											]
			])

	{!! Form::open(array('route' => array('hr.chart.workleaves.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite