@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => 'ORGANISASI ', 'route' => route('hr.organisations.show', [Input::get('org_id'), 'org_id' => Input::get('org_id')]) ]]])
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
										'widget_title'		=> 'Pilih Organisasi :',								
										'organisation_id'	=> Input::get('org_id'),
										'identifier'		=> 1,
										'search'			=> ['withattributes' => 'branches'],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 12,
									]
								]
	])
@overwrite

@section('content_body')
	@include('widgets.common.form_name', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'organisationlist'		=>
									[
										'form_url'			=> route('hr.organisations.store', ['id' => $id]),
										'identifier'		=> 1,
										'search'			=> ['id' => $id],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 1,
										'route_back'		=> route('hr.organisations.show', ['id' => $id, 'org_id' => $id])
									]
								]
	])	
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite