@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
		['breadcrumb' => 	[
								['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
								['name' => 'Template Cuti', 'route' => route('hr.workleaves.index', ['org_id' => $data['id']]) ]
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
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.workleave.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Template Cuti '.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small>' : null),
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'workleavelist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> [],
											'sort'				=> ['name' => 'asc'],
											'page'				=> (Input::has('page') ? Input::get('page') : 1),
											'per_page'			=> 12,
											'route_create'		=> route('hr.workleaves.create', ['org_id' => $data['id']])
										]
									]
	])

	{!! Form::open(array('route' => array('hr.workleaves.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}

@overwrite

@section('content_footer')
@overwrite