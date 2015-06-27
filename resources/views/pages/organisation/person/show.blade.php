@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
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
										'sidebar'				=> 
										[
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100
										]
									]
	])
@overwrite

@section('content_body')
			@include('widgets.contact.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> $person['name'],
				'widget_options'		=> 	[
												'contactlist'			=>
												[
													'search'			=> ['personid' => $person['id']],
													'sort'				=> ['is_default' => 'desc'],
													'page'				=> 1,
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

		{!! Form::open(array('route' => array('hr.person.contacts.delete', 0),'method' => 'DELETE')) !!}
			@include('widgets.modal.delete', [
				'widget_template'		=> 'plain_no_title'
			])
		{!! Form::close() !!}
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite