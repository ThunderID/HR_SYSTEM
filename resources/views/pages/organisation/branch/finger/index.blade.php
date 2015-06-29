@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id'] ])], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
						['name' => 'Absen Sidik Jari', 'route' => route('hr.branch.fingers.index', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
					]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'sidebar'					=> 
										[
											'search'				=> ['withattributes' => 'branches'],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'active_finger_branch'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_body')
	@include('widgets.organisation.branch.fingerprint.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Absen Sidik Jari Cabang "'.$branch['name'].'"',
		'widget_options'		=> 	[
										'fingerprintlist'		=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> ['branchid' => $branch['id']],
											'sort'				=> ['branch_id' => 'asc'],
											'page'				=> 1,
											'per_page'			=> 1,
										]
									]
	])

@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite