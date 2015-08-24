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
		'widget_options'	=> [
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
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			@include('widgets.organisation.dashboard.widget', [
				'widget_template'	=> 'panel_no_title',
				'widget_options'	=> [
											'dashboard'			=> 
											[
												'search'		=> '',
												'sort'			=> [],
												'page'			=> 1,
												'per_page'		=> 100
											]
										]
			])
		</div>
	</div>
@stop

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite