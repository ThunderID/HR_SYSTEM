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
		'widget_options'		=> [
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
	<div class="row mb-25">
		<div class="col-sm-12">
			<a href="javascript:;" class="btn btn-primary" data-toggle="modal" data-target="#add_widget" data-org="{{ $data['id'] }}">Tambah Widget</a>
		</div>
	</div>
	{!! Form::open(['url' => 'javascript:;','method' => 'POST']) !!}
		@include('widgets.modal.modal_add_widget_org', [
			'widget_template'		=> 'plain_no_title',
			'class_id'				=> 'add_widget'
		])
	{!! Form::close() !!}
	<div class="row">
		@include('widgets.organisation.dashboard', [
			'widget_template'		=> 'plain_no_title',
			'widget_title'			=> null,
			'widget_options'		=> [
											'widgetlist'	=> 
											[
												'title'				=> null,
												'organisation_id'	=> $data['id'],
												'search'			=> ['type' => 'all'],
												'sort'				=> [],
												'page'				=> 1,
												'per_page'			=> 100,
											]
									]
		])
	</div>
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite