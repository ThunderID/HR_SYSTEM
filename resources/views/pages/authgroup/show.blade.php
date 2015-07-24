@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Otentikasi Grup', 'route' => route('hr.authgroups.index')],
								['name' => $data['name'], 'route' => route('hr.authgroups.show', $data['id'])],
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
	@include('widgets.groupmenu.table', [
		'widget_title'			=> 'Otentikasi "'.$data['name'].'" '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'menu' 				=>
										[
											'form_url' 			=> null,
											'search'			=> ['withattributes' => 'application', 'hasapplication' => true],
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['application_id' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 30,
											'route_create'		=> route('hr.application.menus.create', ['app_id' => $id])
											]
									]
	])
	
	
@overwrite

@section('content_footer')
@overwrite