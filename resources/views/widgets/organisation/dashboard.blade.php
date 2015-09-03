@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@foreach ($PersonWidgetComposer['widget_data']['widgetlist']['widget'] as $key => $value)
			@if (count($PersonWidgetComposer['widget_data']['widgetlist']['widget'])==4)
				@if (($key%2)==0)
					</div>
					<div class="row">
				@endif
				<div class="col-sm-6">
						@include('widgets.organisation.person.stat.total_employee', [
										'widget_template'		=> '',
										'widget_title'			=> '',
										'widget_options'		=> 	[
																		'personlist'		=>
																		[
																			'title'				=> 'Total Karyawan "'.$data['name'].'"',
																			'organisation_id'	=> $data['id'],
																			'search'			=> ['chartnotadmin' => true],
																			'sort'				=> [],
																			'page'				=> 1,
																			'per_page'			=> 100,
																		]
																	]
									])
				</div>
			@endif
		@endforeach
		
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif