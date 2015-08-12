@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => 'Dokumen', 'route' => route('hr.person.documents.index', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => (is_null($id) ? 'Tambah' : 'Ubah '), 'route' => (is_null($id) ? route('hr.person.documents.create', ['org_id' => $data['id'], 'person_id' => $person['id']]) : route('hr.person.documents.edit', ['org_id' => $data['id'], 'person_id' => $person['id'], 'id' => $id]) )]
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
										'sidebar'						=> 
										[
											'search'					=> [],
											'sort'						=> [],
											'page'						=> 1,
											'per_page'					=> 100,
											'active_document_person' 	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@if(Input::has('doc_id'))	
		@if (count(Input::get('doc_id'))>1)
			<h1> {{ 'Tambah Dokumen '}} "{{$person['name']}}" </h1>
			<hr style="margin-bottom:0">
			<div class="panel panel-default">
				<div class="panel-body">
					@foreach(Input::get('doc_id') as $key => $value)
						@include('widgets.common.persondocument.form_multiple', [
							'widget_template'	=> 'plain',
							'widget_options'	=> 	[
														'documentlist'			=>
														[
															'form_url'			=> route('hr.person.documents.store', ['id' => $id, 'doc_id' => $value, 'person_id' => $person['id'], 'org_id' => $data['id']]),
															'search'			=> ['id' => $value, 'withattributes' => ['templates']],
															'organisation_id'	=> $data['id'],
															'persondocument'	=> $persondocument,
															'sort'				=> [],
															'new'				=> (is_null($id) ? true : false),
															'page'				=> 1,
															'per_page'			=> 1,
															'route_back'	 	=> route('hr.person.documents.index', ['org_id' => $data['id'], 'person_id' => $person['id']])
														]
													]
						])	
					@endforeach
					<div class="form-group text-right">				
						<a href="{{ route('hr.person.documents.index', ['org_id' => $data['id'], 'person_id' => $person['id']]) }}" class="btn btn-default mr-5">Batal</a>
						<input type="submit" class="btn btn-primary" value="Simpan">				
					</div>
				</div>
			</div>
		@else
			@include('widgets.common.persondocument.form', [
				'widget_template'	=> 'panel',
				'widget_options'	=> 	[
											'documentlist'			=>
											[
												'form_url'			=> route('hr.person.documents.store', ['id' => $id, 'doc_id' => Input::get('doc_id'), 'person_id' => $person['id'], 'org_id' => $data['id']]),
												'search'			=> ['id' => Input::get('doc_id'), 'withattributes' => ['templates']],
												'organisation_id'	=> $data['id'],
												'persondocument'	=> $persondocument,
												'sort'				=> [],
												'new'				=> (is_null($id) ? true : false),
												'page'				=> 1,
												'per_page'			=> 1,
												'route_back'	 	=> route('hr.person.documents.index', ['org_id' => $data['id'], 'person_id' => $person['id']])
											]
										]
			])
		@endif
	@else
		@include('widgets.organisation.document.select', [
			'widget_template'	=> 'panel',
			'widget_options'	=> 	[
										'documentlist'			=>
										[
											'form_url'			=> route('hr.person.documents.create', ['id' => $id, 'person_id' => $person['id'], 'org_id' => $data['id']]),
											'organisation_id'	=> $data['id'],
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
										]
									]
		])
	@endif

@overwrite

@section('content_footer')
@overwrite