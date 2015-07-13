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
											'person'				=> 'yes',
											'active_all_person'		=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
	
@overwrite

@section('content_body')
	@include('widgets.organisation.authentication.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Otentikasi',
		'widget_options'		=> [ 'authentication' 				=>
											[
												'form_url' 			=> null,
												'organisation_id'	=> $data['id'],
												'search'			=> [],
												'sort'				=> [],											
												'page'				=> (Input::has('page') ? Input::get('page') : 1),
												'per_page'			=> 12,
												'route_create'		=> route('hr.authentications.create', ['org_id' => $data['id']])								
											]
									]
	])	
	
	{!! Form::open(array('route' => array('hr.branches.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite