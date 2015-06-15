@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], ['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',								
								'organisation_id'	=> 1,
								'document_id'		=> 1,
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
	@include('widgets.common.show_info', [
		'widget_template'		=> 'plain',
		'widget_title'			=> $data['name'],
		'widget_title_class'	=> '',
		'widget_body_class'		=> '',
		'widget_info'			=> 'Total Template Cuti',
		'widget_info_class'		=> 'mb-10',
		'widget_options'		=> ['total'	=> '']		
	])
	
	@include('widgets.person.form', [
		'widget_template'	=> 'panel',
		'widget_options'	=> ['widget_title'		=> 'Tambah Data :',
								'form_url'			=> route('hr.persons.store', ['id' => $id, 'org_id' => $data['id']]),
								'organisation_id'	=> $data['id'],
								'search'			=> ['id' => $id],
								'sort'				=> [],
								'page'				=> 1,
								'per_page'			=> 1,
								]
	])

@overwrite

@section('content_footer')
@overwrite