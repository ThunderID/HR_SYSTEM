@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']])]]])
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
	@include('widgets.branch.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> ''
									]
	])
@overwrite

@section('content_body')	
	@include('widgets.common.show_info', [
		'widget_template'		=> 'plain',
		'widget_title'			=> $data['name'],
		'widget_title_class'	=> '',
		'widget_body_class'		=> '',
		'widget_info'			=> 'Total Cabang',
		'widget_info_class'		=> 'mb-10',
		'widget_options'		=> ['total'	=> count($branches)]		
	])
	
	@include('widgets.branch.table', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> ['form_url' 			=> null,
									'organisation_id'	=> $data['id'],
									'identifier'		=> 1,
									'search'			=> ['defaultcontact' => true],
									'sort'				=> [],
									'page'				=> 1,
									'per_page'			=> 12
									]
	])

	@include('widgets.modal.delete', [
		'widget_template'		=> 'plain_no_title'
	])
@overwrite

@section('content_footer')
@overwrite