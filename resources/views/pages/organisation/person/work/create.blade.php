@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => 'Pekerjaan', 'route' => route('hr.person.works.index', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah' : 'Ubah '), 'route' => (is_null($id) ? route('hr.person.works.create', ['org_id' => $data['id'], 'person_id' => $person['id']]) : route('hr.person.works.edit', ['org_id' => $data['id'], 'person_id' => $person['id'], 'id' => $id]) )]
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
											'search'				=> [],
											'sort'					=> [],
											'page'					=> 1,
											'per_page'				=> 100,
											'active_work_person'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
@if(Input::has('prev'))
	@if(Input::get('prev'))
		@include('widgets.organisation.person.work.experience.form', [
			'widget_template'	=> 'panel',
			'widget_options'	=> 	[
										'worklist'			=>
										[
											'form_url'			=> route('hr.person.works.store', ['id' => $id, 'person_id' => $person['id'], 'org_id' => $data['id']]),
											'organisation_id'	=> $data['id'],
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'new'				=> (is_null($id) ? true : false),
											'page'				=> 1,
											'per_page'			=> 1,
											'route_back'	 	=> route('hr.person.works.index', ['person_id' => $person['id'], 'org_id' => $data['id']])
										]
									]
		])
	@else
		@include('widgets.organisation.person.work.form', [
			'widget_template'	=> 'panel',
			'widget_options'	=> 	[
										'worklist'			=>
										[
											'form_url'			=> route('hr.person.works.store', ['id' => $id, 'person_id' => $person['id'], 'org_id' => $data['id']]),
											'organisation_id'	=> $data['id'],
											'search'			=> ['id' => $id],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 1,
											'new'				=> (is_null($id) ? true : false),
											'route_back'	 	=> route('hr.person.works.index', ['person_id' => $person['id'], 'org_id' => $data['id']])
										]
									]
		])
	@endif
@else
	<div class="clearfix">&nbsp;</div>
	<div class="clearfix">&nbsp;</div>
	<div class="clearfix">&nbsp;</div>
	<div class="clearfix">&nbsp;</div>
	<div class="row">
		<div class="col-sm-4 col-sm-offset-4">
			@include('widgets.common.selectblock', [
				'widget_title'			=> 'Pilih Data',
				'widget_template'		=> 'panel',
				'widget_options'		=> 	[
												'url_old'		=> route('hr.person.works.create', ['org_id' => $data['id'], 'person_id' => $person['id'], 'prev' => true]),
												'url_new'		=> route('hr.person.works.create', ['org_id' => $data['id'], 'person_id' => $person['id'], 'prev' => false]),
												'caption_old'	=> 'Pengalaman Kerja',
												'caption_new'	=> 'Pekerjaan Saat Ini',
											],
				])
		</div>
	</div>
@endif
@overwrite

@section('content_footer')
@overwrite