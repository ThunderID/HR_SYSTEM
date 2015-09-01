@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => 	[
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Template Dokumen', 'route' => route('hr.documents.index', ['org_id' => $data['id']]) ],
							['name' => $document['name'], 'route' => route('hr.documents.show', ['org_id' => $data['id'], 'id' => $document['id']]) ]
						]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
										'sidebar'				=> 
										[
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'pengaturan'		=> 'yes',
											'active_document'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.common.persondocument.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Dokumen "'.$document['name'].'" Karyawan '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'documentlist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> ['documentid' => $document['id'], 'withattributes' => ['person'], 'organisationid' => $data['id']],
											'sort'				=> ['created_at' => 'asc'],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.person.documents.create', ['org_id' => $data['id'], 'doc_id' => $document['id']]),
											'route_read'		=> route('hr.documents.show', $id)
										]
									]
	])

	{!! Form::open(['url' => 'javascript:;','method' => 'POST']) !!}
		@include('widgets.modal.import_csv', [
			'widget_template'		=> 'plain_no_title',
			'class_id'				=> 'import_csv_doc_org'
		])
	{!! Form::close() !!}

	{!! Form::open(array('route' => array('hr.person.documents.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite