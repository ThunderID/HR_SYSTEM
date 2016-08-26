@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => 'IP Whitelist', 'route' => route('hr.ipwhitelists.index')],
								['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.ipwhitelists.create', ['org_id' => $data['id']]) : route('hr.ipwhitelists.edit', ['id' => $id, 'org_id' => $data['id']]) )]
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
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
											'sistem'			=> 'yes',
											'ip_whitelist'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.ipwhitelist.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'IP Whitelist',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'ipwhitelist' 				=>
										[	
											'form_url' 			=> route('hr.ipwhitelists.store', ['id' => $id]),
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'new'				=> (is_null($id) ? true : false),
											'per_page'			=> 1,
											'route_back'		=> route('hr.ipwhitelists.index')
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite