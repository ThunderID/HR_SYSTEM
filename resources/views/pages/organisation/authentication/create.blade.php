@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [ 'sidebar' 						=>
										[
											'search'				=> ['withattributes' => 'branches'],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'pengaturan'			=> 'yes',
											'active_authentication'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.organisation.authentication.form', [
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'workauthlist' 				=>
											[
												'form_url' 			=> route('hr.authentications.store', ['id' => $id]),
												'organisation_id'	=> $data['id'],
												'search'			=> ['id' => $id],
												'sort'				=> [],											
												'page'				=> (Input::has('page') ? Input::get('page') : 1),
												'new'				=> (is_null($id) ? true : false),
												'per_page'			=> 1,
												'route_back'		=> route('hr.authentications.index', ['org_id' => $data['id']])								
											]
									]
	])	
@overwrite

@section('content_footer')
@overwrite