<?php
	if (Route::is('hr.branches.edit')) {
		$title = 'Edit Cabang';
	} else {
		$title = 'Tambah Cabang';
	}
?>

@section('nav_topbar')
	
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> ['identifier'		=> 1,
								'search'			=> [],
								'sort'				=> [],
								'page'				=> 1,
								'per_page'			=> 12,
								]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.branch.form', [
		'widget_template'		=> 'plain',
		'widget_title'			=> $title.' Kantor Cabang',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> ['form_url' 			=> route('hr.branches.store'),
									'organisation_id'	=> $data['organisation_id'],
									'identifier'		=> 1,
									'search'			=> ['id' => $data['id']],
									'sort'				=> [],
									'page'				=> 1,
									'per_page'			=> 1,
									'route_edit'		=> route('hr.branches.index', ['org_id' => $data['organisation_id']])
									]
	])

@overwrite

@section('content_footer')
@overwrite