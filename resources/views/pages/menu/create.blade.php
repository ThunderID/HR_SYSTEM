@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Aplikasi', 'route' => route('hr.applications.index')],
								['name' => $data['name'], 'route' => route('hr.applications.show', $data['id'])],
								['name' => 'Menu', 'route' => route('hr.applications.show', $data['id'])],
								['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.application.menus.create', ['app_id' => $data['id']]) : route('hr.application.menus.edit', ['id' => $id, 'app_id' => $data['id']]) )]
							]
	])
@stop


@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [ 'sidebar' 					=>
										[
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'sistem'			=> 'yes',
											'application'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.menu.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Menu',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'menu' 				=>
										[	
											'form_url' 			=> route('hr.application.menus.store', ['id' => $id, 'app_id' => $data['id']]),
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'new'				=> (is_null($id) ? true : false),
											'per_page'			=> 1,
											'route_back'		=> route('hr.applications.show', Input::get('app_id'))
										],

									'menulist' 				=>
										[	
											'form_url' 			=> null,
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'new'				=> (is_null($id) ? true : false),
											'per_page'			=> 100,
											'route_back'		=> null
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite