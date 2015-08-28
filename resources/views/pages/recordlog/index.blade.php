@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Recordlog', 'route' => route('hr.recordlogs.index')],
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
											'per_page'			=> 100											
										]
									]
	])
@overwrite
@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.recordlog.table', [
		'widget_title'			=> 'Notifikasi',
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'recordlog' 				=>
										[
											'form_url' 			=> null,
											'search'			=> ['level'	=> Session::get('user.menuid'), 'organisationid' => Session::get('user.organisationids'), 'withattributes' => ['person']],
											'sort'				=> [],
											'active_filter'		=> [],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 100
											]
									]
	])
	
	
@overwrite

@section('content_footer')
@overwrite