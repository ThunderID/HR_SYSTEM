@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => 	[
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Template Dokumen', 'route' => route('hr.documents.index', ['org_id' => $data['id']]) ],
							['name' => $document['name'], 'route' => route('hr.documents.show', ['org_id' => $data['id'], 'id' => $document['id']]) ],
							['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.document.templates.create', ['org_id' => $data['id']]) : route('hr.document.templates.edit', ['org_id' => $data['id'], 'id' => $id]) )]
						]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
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

	@include('widgets.document.template.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> 	[
									'templatelist'			=>
									[
										'form_url'			=> route('hr.document.templates.store', ['id' => $id, 'org_id' => $data['id'], 'doc_id' => $document['id']]),
										'organisation_id'	=> $data['id'],
										'document_id'		=> $document['id'],
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.document.templates.index', ['org_id' => $data['id'], 'doc_id' => $document['id']])
									]
								]
	])

@overwrite

@section('content_footer')
@overwrite