@section('nav_topbar')
	@include('widgets.common.nav_topbar', ['breadcrumb' => [['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], ['name' => 'Cabang', 'route' => route('hr.branches.index', ['org_id' => $data['id']]) ]]])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> [ 'sidebar' 					=>
										[
											'search'			=> ['withattributes' => 'branches'],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])
@overwrite

@section('content_filter')
	@include('widgets.common.filter', [
		'widget_template'		=> 'plain_no_title',
		'widget_options'		=> [
									'form_url'	=> ''
									]
	])
@overwrite

@section('content_body')	
	@section('content_body')
	@include('widgets.branch.alert', [
		'widget_template'		=> 'plain_no_title'
	])

	@include('widgets.branch.table', [
		'widget_title'			=> 'Cabang '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_template'		=> 'panel',
		'widget_options'		=> [ 'branchlist' 				=>
										[
											'form_url' 			=> null,
											'organisation_id'	=> $data['id'],
											'search'			=> ['defaultcontact' => true],
											'sort'				=> [],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12
											'route_create'		=> route('hr.branches.create', ['org_id' => $data['id']])
											]
									]
	])
	
	{!! Form::open(array('route' => array('hr.branches.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_footer')
@overwrite