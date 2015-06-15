@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => 'ORGANISATION NAME']]])
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

@section('content_body')
	@include('widgets.common.show_info', [
		'widget_template'		=> 'plain',
		'widget_title'			=> $data[0]['name'],
		'widget_title_class'	=> '',
		'widget_body_class'		=> '',
		'widget_info'			=> 'Total Kontak',
		'widget_info_class'		=> 'mb-10',
		'widget_options'		=> []		
	])

	@include('widgets.branch.detail', [
		'widget_template'		=> 'plain_no_title',
		'widget_body_class'		=> '',
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite