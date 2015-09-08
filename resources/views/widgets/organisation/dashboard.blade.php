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
		@else
			<div class="col-sm-6 col-md-6">
				@include('widgets.organisation.person.stat.total_employee', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Total Karyawan '.$data['name'],
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
			<div class="col-sm-6 col-md-6">
				@include('widgets.organisation.branch.stat.total_branch', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Total Cabang '.$data['name'],
					'widget_options'		=> 	[
													'branchlist'		=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> [],
														'page'				=> 1,
														'per_page'			=> 100,
													]
												]
				])
			</div>
			<div class="col-sm-6 col-md-6">
				@include('widgets.organisation.document.stat.total_document', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Total Dokumen '.$data['name'],
					'widget_options'		=> 	[
													'documentlist'		=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> [],
														'page'				=> 1,
														'per_page'			=> 100,
													]
												]
				])
			</div>
			<div class="col-sm-6 col-md-6">
				@include('widgets.organisation.person.stat.average_loss_rate', [
					'widget_template'		=> 'plain',
					'widget_title'			=> 'Average Loss Rate '.$data['name'],
					'widget_options'		=> 	[
													'lossratelist'		=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['globalattendance' => ['organisationid' => $data['id'], 'on' => [$start, $end]]],
														'sort'				=> [],
														'page'				=> 1,
														'per_page'			=> 40,
													]
												]
				])
			</div>
		</div>
		<div class="row mt-20">
			<div class="col-sm-12">
				@include('widgets.organisation.person.table', [
					'widget_template'		=> 'panel',
					'widget_title'			=> '<h4>Absen Karyawan "'.$data['name'].'" ('.date('d-m-Y', strtotime('- 1 day')).')</h4>',
					'widget_options'		=> 	[
													'personlist'		=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['fullschedule' => date('Y-m-d', strtotime('- 1 day')), 'withattributes' => ['works.branch']],
														'sort'				=> [],
														'page'				=> 1,
														'per_page'			=> 12,
														'route_create'		=> route('hr.persons.create', ['org_id' => $data['id']])
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