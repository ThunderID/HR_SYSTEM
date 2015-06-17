@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => $person['name'], 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => 'Karir', 'route' => route('hr.person.works.index', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
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
											'per_page'			=> 12,
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.work.table', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Karir',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'worklist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> ['personid' => $person['id'], 'withattributes' => ['chart', 'chart.branch', 'chart.branch.organisation']],
											'sort'				=> ['end' => 'asc'],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])

@overwrite

@section('content_footer')
@overwrite