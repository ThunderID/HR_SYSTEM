@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
							]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> [
									'sidebar'				=>
									[
										'search'			=> ['id' => Session::get('user.organisationids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 100,
										'active_dashboard'	=> 'yes'
									]
								]
	])
@overwrite

@section('content_body')
	@include('widgets.organisation.select', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'organisationlist'	=> 
									[
										'form_url'			=> (Session::get('user.menuid')!=4 ? route('hr.organisations.show', 1) : route('hr.persons.index', 1)),
										'search'			=> ['id' => Session::get('user.organisationids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 100,
									]
								]
	])	
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite
