@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.show', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
						['name' => 'Jadwal Kerja', 'route' => route('hr.person.schedules.index', ['id' => $person['id'], 'person_id' => $person['id'],'org_id' => $data['id'] ])], 
					]
	])
@stop

@section('nav_sidebar')
	@include('widgets.common.nav_sidebar', [
		'widget_template'		=> 'plain_no_title',
		'widget_title'			=> 'Structure',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'sidebar'						=> 
										[
											'search'					=> [],
											'sort'						=> [],
											'page'						=> 1,
											'per_page'					=> 100,
											'active_schedule_person'	=> 'yes'
										]
									]
	])
@overwrite

@section('content_filter')
@overwrite

@section('content_body')	
	@include('widgets.organisation.person.schedule.calendar', [
		'widget_template'		=> 'panel',
		'widget_title'			=> 'Jadwal Kerja "'.$person['name'].'"',		
		'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
		'widget_body_class'		=> '',
		'widget_options'		=> 	[
										'schedulelist'			=>
										[
											'organisation_id'	=> $data['id'],
											'search'			=> [],
											'sort'				=> ['end' => 'asc'],
											'page'				=> 1,
											'per_page'			=> 12,
										]
									]
	])

	{!! Form::open(array('url' => route('hr.person.schedules.store', ['org_id' => $data['id'], 'person_id' => $person['id']]), 'method' => 'POST', 'class' => 'no_enter')) !!}
		@include('widgets.modal.modal_create_schedule', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}

	{!! Form::open(array('route' => array('hr.person.schedules.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
	
	<script type="text/javascript">
		var cal_link = "{!! route('hr.person.schedule.ajax',['org_id' => $data['id'],'person_id' => $person['id']]) !!}";
	</script>	
@overwrite

@section('content_footer')
@overwrite