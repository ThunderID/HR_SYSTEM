@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => 'ORGANISASI ', 'route' => route('hr.organisations.show', [Input::get('org_id'), 'org_id' => Input::get('org_id')]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'	=> ['widget_title'		=> 'Pilih Organisasi :',								
								'organisation_id'	=> Input::get('org_id'),
								'identifier'		=> 1,
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
	<p class="text-center font-34 " style="height:580px; margin-top:15%;">Under <br> Construction</p>
@overwrite

@section('content_footer')
@overwrite