<?php
	if($id) {
		$active = 'active_edit_branch';
	} else {
		$active = 'active_create_branch';
	}
?>

@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' 			=> 	[
										['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
										['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id']]) ],
										['name' => (is_null($id) ? 'Tambah' : 'Ubah'), 'route' => (is_null($id) ? route('hr.branches.create', ['org_id' => $data['id']]) : route('hr.branches.edit', ['id' => $id, 'org_id' => $data['id']]) )]
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
											'active_form'		=> $active,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	


	@include('widgets.organisation.branch.form', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Cabang',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [
									'branchlist' 				=>
										[	
											'form_url' 			=> route('hr.branches.store', ['id' => $id, 'org_id' => $data['id']]),
											'organisation_id'	=> $data['id'],
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 1,
											'route_edit'		=> route('hr.branches.index', ['org_id' => $data['id'], 'branch_id' => 0])
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite