@section('nav_topbar')
	@include('widgets.common.nav_topbar', 
	['breadcrumb' => [
						['name' => $data['name'], 'route' => route('hr.organisations.show', [$data['id'], 'org_id' => $data['id']]) ], 
						['name' => 'Data Karyawan', 'route' => route('hr.persons.index', ['org_id' => $data['id']]) ],
						['name' => $person['name'], 'route' => route('hr.persons.index', ['org_id' => $data['id'] ])], 
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
										'sidebar'				=> 
										[
											'search'			=> [],
											'sort'				=> [],
											'page'				=> 1,
											'per_page'			=> 100
										]
									]
	])
@overwrite

@section('content_body')
	<!-- <div class="row">
		<div class="col-sm-12">
			<div class='alert alert-info mt-10 '>
				<div class="row">
					<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
						<i class="fa fa-hand-pointer-o" style="font-size:40px"></i>
					</div>
					<div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
						<p class="mt-10">Untuk Absen sidik jari hari ini <b>Telunjuk</b></p>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	<div class="row">
		@include('widgets.organisation.person.dashboard', [
			'widget_template'		=> 'plain_no_title',
			'widget_title'			=> null,
			'widget_options'		=> [
											'widgetlist'	=> 
											[
												'title'				=> null,
												'organisation_id'	=> $data['id'],
												'search'			=> ['dashboard' => 'person'],
												'sort'				=> [],
												'page'				=> 1,
												'per_page'			=> 100,
											]
									]
		])
	</div>

	{!! Form::open(array('route' => array('hr.person.works.delete', 0),'method' => 'DELETE')) !!}
		@include('widgets.modal.delete', [
			'widget_template'		=> 'plain_no_title'
		])
	{!! Form::close() !!}
@overwrite

@section('content_filter')
@overwrite

@section('content_footer')
@overwrite