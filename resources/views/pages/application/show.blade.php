@section('nav_topbar')
	
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [ 'sidebar' 					=>
										[
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'sistem'			=> 'yes',
											'auth_group'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.menu.table', [
		'widget_title'			=> 'Menu Aplikasi',
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'menu' 				=>
										[
											'form_url' 			=> null,
											'search'			=> [],
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['tag' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.menus.create')
											]
									]
	])
	
	
@overwrite

@section('content_footer')
@overwrite