@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.index', ['org_id' => $data['id'] ])], 
						['name' => 'Dokumen', 'route' => route('hr.person.documents.index', ['person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => $document['name'], 'route' => route('hr.person.documents.show', ['id' => $id, 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
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
	<div class="row">
		<div class="col-sm-12">
			@include('widgets.common.persondocument.document', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Dokumen "'.$person['name'].'"',
				'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
				'widget_body_class'		=> '',
				'widget_options'		=> 	[
												'documentlist'			=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> ['personid' => $person['id'], 'withattributes' => ['details', 'details.template']],
													'sort'				=> ['created_at' => 'asc'],
													'page'				=> 1,
													'per_page'			=> 1,
													'route_create'		=> route('hr.person.documents.create', ['org_id' => $data['id'], 'person_id' => $person['id']])
												]
											]
			])
		</div>
	</div>

	
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite