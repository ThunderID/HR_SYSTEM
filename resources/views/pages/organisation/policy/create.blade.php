@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Pengaturan Kebijakan', 'route' => route('hr.policies.index', ['org_id' => $data['id']]) ],
										['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.policies.create', ['org_id' => $data['id']]) : route('hr.policies.edit', ['org_id' => $data['id'], 'id' => $id]) )]
									]
		])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [ 'sidebar' 					=>
										[
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'pengaturan'		=> 'yes',
											'active_policy'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.policy.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Kebijakan',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'policylist' 				=>
										[	
											'form_url' 			=> route('hr.policies.store', ['id' => $id, 'org_id' => $data['id']]),
											'organisation_id'	=> $data['id'],
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'new'				=> (is_null($id) ? true : false),
											'per_page'			=> 1,
											'route_edit'		=> route('hr.policies.index', ['org_id' => $data['id']])
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite