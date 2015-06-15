@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], ['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
										'identifier'		=> 1,
										'search'			=> [],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 12,
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.branch.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Cabang',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'form_url' 			=> route('hr.branches.store', ['id' => $id , 'org_id' => Input::get('org_id')]),
									'identifier'		=> 1,
									'organisation_id'	=> $data['id'],
									'search'			=> [],
									'sort'				=> [],
									'page'				=> 1,
									'per_page'			=> 1
									]
	])

@overwrite

@section('content_footer')
@overwrite