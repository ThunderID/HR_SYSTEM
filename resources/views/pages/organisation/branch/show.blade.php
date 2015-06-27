@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id'] ])], 
						['name' => $branch['name'], 'route' => route('hr.branches.show', ['id' => $branch['id'], 'branch_id' => $branch['id'],'org_id' => $data['id'] ])], 
					]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'sidebar'				=> 
										[
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100
										]
									]
	])
@overwrite

@section('content_body')
	<div class="row">
		<div class="col-sm-6">
			@include('widgets.organisation.person.stat.total_employee', [
				'widget_template'		=> 'panel',
				'widget_title'			=> 'Total Karyawan '.$data['name'],
				'widget_options'		=> 	[
												'personlist'		=>
												[
													'title'				=> 'Total Karyawan "'.$data['name'].'" Cabang "'.$branch['name'].'"',
													'organisation_id'	=> $data['id'],
													'search'			=> ['currentwork' => null, 'branchid' => $branch['id']],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 100,
												]
											]
			])
		</div>
		<div class="col-sm-6">
			@include('widgets.organisation.branch.fingerprint.block', [
				'widget_template'		=> 'panel',
				'widget_title'			=> '<h4>Absen Sidik Jari Hari Ini Cabang "'.$branch['name'].'"</h4>',
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
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			@include('widgets.common.contact.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> '<h4>Kontak Aktif Cabang "'.$branch['name'].'"</h4>',
				'widget_options'		=> 	[
												'contactlist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> ['default' => true, 'branchid' => $branch['id']],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 100,
													'route'				=> route('hr.branch.contacts.index'),
													'route_create'		=> route('hr.branch.contacts.create', ['org_id' => $data['id'], 'branch_id' => $branch['id']]),
													'route_edit'		=> 'hr.branch.contacts.edit',
													'route_delete'		=> 'hr.branch.contacts.delete',
													'next'				=> 'branch_id',
													'nextid'			=> $branch['id']
												]
											]
			])
		</div>
	</div>

@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite