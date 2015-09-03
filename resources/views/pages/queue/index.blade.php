@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'Queue', 'route' => route('hr.queue.index')],
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
	@include('widgets.queue.table', [
		'widget_title'			=> 'Queue Batch',
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'queue' 				=>
										[
											'form_url' 			=> null,
											'search'			=> ['withattributes' => 'person'],	
											'sort'				=> ['created_at' => 'desc'],
											'active_filter'		=> [],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12
											]
									]
	])
	
	
@overwrite

@section('content_footer')
@overwrite