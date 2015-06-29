@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => 'Jatah Cuti', 'route' => route('hr.person.workleaves.index', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah' : 'Ubah '), 'route' => (is_null($id) ? route('hr.person.workleaves.create', ['org_id' => $data['id'], 'person_id' => $person['id']]) : route('hr.person.workleaves.edit', ['org_id' => $data['id'], 'person_id' => $person['id'], 'id' => $id]) )]
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
										'sidebar'						=> 
										[
											'search'					=> [],
											'sort'						=> [],
											'page'						=> 1,
											'per_page'					=> 100,
											'active_workleave_person'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.person.workleave.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'personworkleavelist'			=>
									[
										'form_url'			=> route('hr.person.workleaves.store', ['id' => $id, 'person_id' => $person['id'], 'org_id' => $data['id']]),
										'organisation_id'	=> $data['id'],
										'search'			=> ['id' => $id ,'withattributes' => ['person']],
										'sort'				=> [],
										'new'				=> (is_null($id) ? true : false),
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'	 	=> route('hr.person.workleaves.index', [$person['id'], 'org_id' => $data['id'], 'person_id' => $person['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite