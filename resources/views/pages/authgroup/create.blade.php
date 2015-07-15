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
											'search'			=> [],
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
	@include('widgets.authgroup.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Aplikasi',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'authgroup' 				=>
										[	
											'form_url' 			=> route('hr.authgroups.store', ['id' => $id]),
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'new'				=> (is_null($id) ? true : false),
											'per_page'			=> 1,
											'route_back'		=> route('hr.authgroups.index')
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite