@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'API', 'route' => route('hr.branch.apis.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
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
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.api.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'apilist'				=>
									[
										'form_url'			=> route('hr.branch.apis.store', ['id' => $id, 'branch_id' => $branch['id'], 'org_id' => $data['id']]),
										'branch_id'			=> $branch['id'],
										'identifier'		=> 1,
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.branch.apis.index', ['branch_id' => $branch['id'], 'org_id' => $data['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite