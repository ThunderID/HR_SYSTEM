@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Kontak', 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
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
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 12
										]
									]
	])
@overwrite

@section('content_body')
			@include('widgets.contact.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Kontak Cabang '.$branch['name'].((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
				'widget_options'		=> 	[
												'contactlist'			=>
												[
													'search'			=> ['branchid' => $branch['id']],
													'sort'				=> ['is_default' => 'desc'],
													'page'				=> (Input::has('page') ? Input::get('page') : 1),
													'per_page'			=> 12,
													'route'				=> route('hr.branch.contacts.index'),
													'route_create'		=> route('hr.branch.contacts.create', ['org_id' => $data['id'], 'branch_id' => $branch['id']]),
													'route_delete'		=> 'hr.branch.contacts.delete',
													'next'				=> 'branch_id',
													'nextid'			=> $branch['id']
												]
											]
			])

	{!! Form::open(array('route' => array('hr.branch.contacts.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite