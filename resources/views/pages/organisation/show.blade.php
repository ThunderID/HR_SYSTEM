@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => $organisation['name'], 'route' => route('hr.organisations.show', [$organisation['id'], 'org_id' => $organisation['id']])],
								['name' => 'Dashboard', 'route' => null]
							]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> [
									'sidebar'				=>
									[
										'search'			=> ['id' => Config::get('user.orgids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 12,
									]
								]
	])
@overwrite

@section('content_body')
	<p class="text-center font-34 " style="height:580px; margin-top:15%;">Under <br> Construction</p>
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite