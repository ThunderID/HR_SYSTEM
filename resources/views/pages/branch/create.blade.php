@section('nav_topbar')
	
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',								
								'organisation_id'	=> 1,
								'identifier'		=> 1,
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
	@include('widgets.branch.form', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Tambah Kantor Cabang',
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> ['form_url' 			=> null,
									'organisation_id'	=> $data['id'],
									'search'			=> ['defaultcontact' => true],
									'sort'				=> [],
									'page'				=> 1,
									'per_page'			=> 12
									]
	])

@overwrite

@section('content_footer')
@overwrite