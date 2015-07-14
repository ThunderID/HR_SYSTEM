@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	[
		'breadcrumb' 	=> 	[
								['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']])],
								['name' => 'Dashboard', 'route' => null]
							]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> [
									'sidebar'				=>
									[
										'search'			=> ['id' => Session::get('user.organisationids')],
										'sort'				=> [],
										'page'				=> 1,
										'per_page'			=> 100,
										'active_dashboard'	=> 'yes'
									]
								]
	])
@overwrite

@section('content_body')
	<div class="row">
		<div class="col-sm-6 col-md-6">
			@include('widgets.organisation.person.stat.total_employee', [
				'widget_template'		=> 'plain',
				'widget_title'			=> 'Total Karyawan '.$data['name'],
				'widget_options'		=> 	[
												'personlist'		=>
												[
													'title'				=> 'Total Karyawan "'.$data['name'].'"',
													'organisation_id'	=> $data['id'],
													'search'			=> ['chartnotadmin' => true],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 100,
												]
											]
			])
		</div>
		<div class="col-sm-6 col-md-6">
			@include('widgets.organisation.branch.stat.total_branch', [
				'widget_template'		=> 'plain',
				'widget_title'			=> 'Total Cabang '.$data['name'],
				'widget_options'		=> 	[
												'branchlist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> [],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 100,
												]
											]
			])
		</div>
		<div class="col-sm-6 col-md-6">
			@include('widgets.organisation.document.stat.total_document', [
				'widget_template'		=> 'plain',
				'widget_title'			=> 'Total Dokumen '.$data['name'],
				'widget_options'		=> 	[
												'documentlist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> [],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 100,
												]
											]
			])
		</div>
		<div class="col-sm-6 col-md-6">
			@include('widgets.organisation.person.stat.average_loss_rate', [
				'widget_template'		=> 'plain',
				'widget_title'			=> 'Average Loss Rate '.$data['name'],
				'widget_options'		=> 	[
												'lossratelist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> ['globalattendance' => ['organisationid' => $data['id'], 'on' => [$start, $end]]],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 40,
												]
											]
			])
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			@include('widgets.organisation.person.table', [
				'widget_template'		=> 'panel',
				'widget_title'			=> '<h4>Absen Karyawan "'.$data['name'].'" ('.date('d-m-Y', strtotime('- 1 day')).')</h4>',
				'widget_options'		=> 	[
												'personlist'		=>
												[
													'organisation_id'	=> $data['id'],
													'search'			=> ['fullschedule' => date('Y-m-d', strtotime('- 1 day')), 'withattributes' => ['works.branch']],
													'sort'				=> [],
													'page'				=> 1,
													'per_page'			=> 40,
													'route_create'		=> route('hr.persons.create', ['org_id' => $data['id']])
												]
											]
			])
		</div>
	</div>
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite