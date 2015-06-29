@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id'] ])], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'API', 'route' => route('hr.branch.apis.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
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
										'sidebar'				=>
										[
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'active_api_branch'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> route('hr.branch.apis.index', ['org_id' => $data['id'], 'branch_id' => $branch['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
	])
@overwrite

@section('content_body')
	@include('widgets.organisation.branch.api.table', [
		'widget_title'			=> 'API Cabang "'.$branch['name'].'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_template'		=> 'panel',
		'widget_options'		=> 	[
										'apilist'				=>
										[
											'branch_id'			=> $branch['id'],
											'search'			=> array_merge(['branchid' => $branch['id']], (isset($filtered['search']) ? $filtered['search'] : [])),
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['client' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.branch.apis.create', ['org_id' => $data['id'], 'branch_id' => $branch['id']]),
											'route_back'		=> route('hr.branch.apis.index', ['org_id' => $data['id'], 'branch_id' => $branch['id']])
										]
									]
	])

	{!! Form::open(array('route' => array('hr.branch.apis.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite