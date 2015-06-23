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
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.document.template.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> $document['name'],
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'templatelist'			=>
										[
											'organisation_id'	=> $data['id'],
											'document_id'		=> $id,
											'search'			=> [],
											'sort'				=> ['id' => 'asc'],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 100,
											'route_create'		=> route('hr.document.templates.create', ['org_id' => $data['id'], 'doc_id' => $document['id']])
										]
									]
	])

	{!! Form::open(array('route' => array('hr.document.templates.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite