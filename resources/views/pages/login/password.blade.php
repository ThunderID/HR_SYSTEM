@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Ubah Password', 'route' => route('hr.password.get') ], 
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
	@include('widgets.form.form_password', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'login' 				=> 
									[
										'widget_title'		=> 'Ubah Password',
										'form_url'			=> route('hr.password.post'),
									]
								]
	])		
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite
