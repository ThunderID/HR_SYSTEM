@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.index', ['org_id' => $data['id'] ])], 
						['name' => 'Kontak', 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
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
											'search'				=> [],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'active_contact_person' => 'yes'
										]
									]
	])
@overwrite


@section('content_filter')
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> route('hr.person.contacts.index', ['org_id' => $data['id'], 'person_id' => $person['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
	])
@overwrite

@section('content_body')
	@if((int)Session::get('user.menuid')>=4)
			@include('widgets.common.contact.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Kontak "'.$person['name'].'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
				'widget_options'		=> 	[
												'contactlist'			=>
												[
													'search'			=> array_merge(['default' => true,'personid' => $person['id']], (isset($filtered['search']) ? $filtered['search'] : [])),
													'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['is_default' => 'desc']),
													'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
													'page'				=> (Input::has('page') ? Input::get('page') : 1),
													'per_page'			=> 12,
													'route'				=> route('hr.person.contacts.index'),
													'route_create'		=> route('hr.person.contacts.create', ['org_id' => $data['id'], 'person_id' => $person['id']]),
													'route_edit'		=> 'hr.person.contacts.edit',
													'route_delete'		=> 'hr.person.contacts.delete',
													'next'				=> 'person_id',
													'nextid'			=> $person['id']

												]
											]
			])
	@else
		@include('widgets.common.contact.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Kontak "'.$person['name'].'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
				'widget_options'		=> 	[
												'contactlist'			=>
												[
													'search'			=> array_merge(['personid' => $person['id']], (isset($filtered['search']) ? $filtered['search'] : [])),
													'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['is_default' => 'desc']),
													'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
													'page'				=> (Input::has('page') ? Input::get('page') : 1),
													'per_page'			=> 12,
													'route'				=> route('hr.person.contacts.index'),
													'route_create'		=> route('hr.person.contacts.create', ['org_id' => $data['id'], 'person_id' => $person['id']]),
													'route_edit'		=> 'hr.person.contacts.edit',
													'route_delete'		=> 'hr.person.contacts.delete',
													'next'				=> 'person_id',
													'nextid'			=> $person['id']

												]
											]
			])
	@endif
		{!! Form::open(array('route' => array('hr.person.contacts.delete', 0),'method' => 'DELETE')) !!}
			@include('widgets.modal.delete', [
				'widget_template'		=> 'plain_no_title'
			])
		{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite