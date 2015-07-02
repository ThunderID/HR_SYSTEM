@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Kontak', 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.branch.contacts.create', ['org_id' => $data['id'], 'branch_id' => $branch['id']]) : route('hr.branch.contacts.edit', ['org_id' => $data['id'], 'branch_id' => $branch['id'], 'id' => $id]) )]
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
											'search'				=> ['withattributes' => 'branches'],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'active_contact_branch' => 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.common.contact.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'contactlist'			=>
									[
										'form_url'			=> route('hr.branch.contacts.store', ['id' => $id, 'branch_id' => $branch['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id ,'withattributes' => ['branch']],
										'new'				=> (is_null($id) ? true : false),
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.branches.show', [$branch['id'], 'org_id' => $data['id'], 'branch_id' => $branch['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite