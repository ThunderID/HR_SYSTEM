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
											'application'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.application.table', [
		'widget_title'			=> 'Aplikasi',
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'application' 				=>
										[
											'form_url' 			=> null,
											'search'			=> [],
											'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['name' => 'asc']),
											'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.applications.create')
											]
									]
	])
	
	{!! Form::open(array('route' => array('hr.applications.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite