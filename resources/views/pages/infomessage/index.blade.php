@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Info Pesan Error Device', 'route' => route('hr.infomessage.index')],
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
											'info_device'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.common.errormessagelegend')
@overwrite

@section('content_footer')
@overwrite