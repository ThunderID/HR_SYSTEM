@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => 	[
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Pengaturan Kebijakan', 'route' => route('hr.policies.index', ['org_id' => $data['id']]) ]
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
											'active_policy'		=> 'yes',
											'pengaturan'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> route('hr.policies.index', ['org_id' => $data['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									]
	])
@overwrite

@section('content_body')	
	@include('widgets.organisation.policy.table', [
		'widget_title'			=> 'Pengaturan Kebijakan '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'policylist' 				=>
										[
											'form_url' 			=> null,
											'organisation_id'	=> $data['id'],
											'search'			=> array_merge(['withattributes' => ['createdby']], (isset($filtered['search']) ? $filtered['search'] : [])),
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['created_at' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.policies.create', ['org_id' => $data['id']])
											]
									]
	])
	
	{!! Form::open(array('route' => array('hr.policies.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite