@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_body')
		@if (count($PersonWidgetComposer['widget_data']['widgetlist']['widget'])!=0)
			<?php $tot_ws = 0; ?>
			@foreach ($PersonWidgetComposer['widget_data']['widgetlist']['widget'] as $value)
				@if ($value['type']=='stat')
					@if (($tot_ws%2)==0)
						</div>
						<div class="row">
					@endif
					<?php $x = json_decode($value['query'], 500);?>
					<div class="col-sm-6 box-widgets">
						<div class="action-widget hide">
							<a href="javascript:;" data-target="#add_widget" data-toggle="modal">
								<i class="fa fa-pencil"></i>
							</a>
							<a href="javascript:;" data-toggle="modal" data-target="#del_widget" data-delete-action="{{ route('hr.person.widgets.delete', $value['id']) }}" title="hapus">
								<i class="fa fa-times-circle"></i>
							</a>
						</div>
						@include($value['widget'], $x)
					</div>
					<?php $tot_ws++; ?>
				@endif
			@endforeach
			</div>
			<div class="row">
				<?php $tot_ws = 0; ?>
				@foreach ($PersonWidgetComposer['widget_data']['widgetlist']['widget'] as $key => $value)
					@if ($value['type']!='stat')
						<?php $x = json_decode($value['query'], 500);?>
						<div class="col-sm-12 box-widgets">
							<div class="action-widget hide">
								<a href="javascript:;" data-target="#add_widget" data-toggle="modal">
									<i class="fa fa-pencil"></i>
								</a>
								<a href="javascript:;" data-toggle="modal" data-target="#del_widget" data-delete-action="{{ route('hr.person.widgets.delete', $value['id']) }}" title="hapus">
									<i class="fa fa-times-circle"></i>
								</a>
							</div>
							@include($value['widget'], $x)
						</div>
						<?php $tot_ws++; ?>
					@endif
				@endforeach
			</div>
		@else
			<div class="col-sm-6">
				@include('widgets.organisation.person.workleave.left_quota', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Sisa Cuti "'.$person['name'].'"',
					'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
					'widget_body_class'		=> '',
					'widget_options'		=> 	[
													'personlist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['id' => $person['id'], 'globalworkleave' => array_merge(['organisationid' => $data['id'], 'on' => date('Y-m-d')], (isset($filtered['search']) ? $filtered['search'] : []))],
														'sort'				=> (isset($filtered['sort']) ? $filtered['sort'] : ['persons.name' => 'asc']),
														'page'				=> 1,
														'active_filter'		=> (isset($filtered['active']) ? $filtered['active'] : null),
														'per_page'			=> 1,
													]
												]
				])
			</div>
			<div class="col-sm-6">
				@include('widgets.organisation.person.stat.average_loss_rate', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Average Loss Rate "'.$person['name'].'" Bulan Ini',
					'widget_options'		=> 	[
													'lossratelist'		=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['id' => $person['id'], 'globalattendance' => ['organisationid' => $data['id'], 'on' => [date('Y-m-d', strtotime('first day of this month')), date('Y-m-d', strtotime('first day of next month'))]]],
														'sort'				=> [],
														'page'				=> 1,
														'per_page'			=> 100,
													]
												]
				])
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				@include('widgets.organisation.person.work.table', [
					'widget_template'		=> 'panel',
					'widget_title'			=> '<h4>Pekerjaan Saat Ini "'.$person['name'].'"'.((Input::has('page') && (int)Input::get('page') > 1) ? '<small class="font-16"> Halaman '.Input::get('page').'</small></h4>' : null),
					'widget_title_class'	=> 'text-uppercase ml-10 mt-20',
					'widget_body_class'		=> '',
					'widget_options'		=> 	[
													'worklist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['personid' => $person['id'], 'withattributes' => ['chart', 'chart.branch', 'chart.branch.organisation']],
														'sort'				=> ['end' => 'asc'],
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'route_create'		=> route('hr.person.works.create', ['org_id' => $data['id'], 'person_id' => $person['id']]),
													]
												]
				])
			</div>
		@endif
		
	@overwrite
@else
	@section('widget_body')
	@overwrite
@endif