@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => 	[
							['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
							['name' => 'Template Dokumen', 'route' => route('hr.documents.index', ['org_id' => $data['id']]) ]
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
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> route('hr.documents.index', ['org_id' => $data['id'], 'page' => (Input::has('page') ? Input::get('page') : 1)])
									],
	])
@overwrite
@section('content_body')	
	@include('widgets.organisation.document.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Template Dokumen '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'documentlist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> array_merge([], (isset($filtered['search']) ? $filtered['search'] : [])),
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['name' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.documents.create', ['org_id' => $data['id']])
										]
									]
	])

	{!! Form::open(['url' => 'javascript:;','method' => 'POST']) !!}
		@include('widgets.modal.import_csv', [
			'widget_template'		=> 'plain_no_title',
			'class_id'				=> 'import_csv_doc_org'
		])
	{!! Form::close() !!}

	{!! Form::open(array('route' => array('hr.documents.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite