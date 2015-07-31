@section('nav_topbar')
	@if(isset($workleave))
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Template Cuti', 'route' => route('hr.workleaves.index', ['org_id' => $data['id']]) ],
										['name' => $workleave['name'], 'route' => route('hr.workleaves.index', ['org_id' => $data['id']]) ],
										['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.workleaves.batch.create', ['org_id' => $data['id'], 'workleave_id' => $workleave['id']]) : route('hr.workleaves.edit', ['id' => $id, 'org_id' => $data['id']]) )]
									]
		])
	@else
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Template Cuti', 'route' => route('hr.workleaves.index', ['org_id' => $data['id']]) ],
										['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => route('hr.workleaves.edit', ['id' => $id, 'org_id' => $data['id']])]
									]
		])
	@endif
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
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'pengaturan'		=> 'yes',
											'active_cuti'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.workleave.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'workleavelist'			=>
									[
										'form_url'			=> route('hr.workleaves.store', ['id' => $id, 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'new'				=> (is_null($id) ? true : false),
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.workleaves.index', ['org_id' => $data['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite