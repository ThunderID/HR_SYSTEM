<?php
	if($id){
		$active = 'active_edit_org';
	} else {
		$active = 'active_create_org';
	}
?>

@section('nav_topbar')
	@if(is_null($id))
		@include('widgets.common.nav_topbar', 
		[
			'breadcrumb' 	=> 	[
									['name' => 'Tambah Organisasi ', 'route' => '']
								]
		])
	@else
		@include('widgets.common.nav_topbar', 
		[
			'breadcrumb' 	=> 	[
									['name' => $organisation['name'], 'route' => route('hr.organisations.show', [$organisation['id'], 'org_id' => $organisation['id']])],
									['name' => 'Ubah', 'route' => null]
								]
		])
	@endif
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'sidebar'				=>
									[
										'search'			=> ['id' => Session::get('user.organisationids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 100,
										'active_form'		=> $active
									]
								]
	])
@overwrite

@section('content_body')
	@include('widgets.organisation.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> [
									'organisationlist'		=>
									[
										'form_url'			=> route('hr.organisations.store', ['id' => $id]),
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