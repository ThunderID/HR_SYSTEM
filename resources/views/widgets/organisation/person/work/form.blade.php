@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Karir ' : 'Ubah Karir '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $WorkComposer['widget_data']['worklist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Posisi</label>
				</div>	
				<div class="col-md-10">
					@include('widgets.organisation.branch.chart.select', [
						'widget_options'		=> 	[
														'chartlist'			=>
														[
															'organisation_id'	=> $data['id'],
															'search'			=> ['withattributes' => ['branch']],
															'sort'				=> ['name' => 'asc'],
															'page'				=> 1,
															'per_page'			=> 100,
															'chart_id'			=> $WorkComposer['widget_data']['worklist']['work']['chart_id'],
															'tabindex'			=> 1,
														]
													]
					])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Calendar</label>
				</div>	
				<div class="col-md-10">
					@include('widgets.organisation.calendar.select', [
						'widget_options'		=> 	[
														'calendarlist'			=>
														[
															'organisation_id'	=> $data['id'],
															'search'			=> [],
															'sort'				=> ['name' => 'asc'],
															'page'				=> 1,
															'per_page'			=> 100,
															'calendar_id'		=> $WorkComposer['widget_data']['worklist']['work']['calendar_id'],
															'tabindex'			=> 2,
														]
													]
					])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Status</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'status', $WorkComposer['widget_data']['worklist']['work']['status'], ['class' => 'form-control', 'tabindex' => 3])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Start</label>
				</div>	
				<div class="col-md-4">
					{!!Form::input('text', 'start', $WorkComposer['widget_data']['worklist']['work']['start'], ['class' => 'form-control date-mask', 'tabindex' => 4])!!}
				</div>
				<div class="col-md-1 col-md-offset-1">
					<label class="control-label">End</label>
				</div>	
				<div class="col-md-4">
					{!!Form::input('text', 'end', $WorkComposer['widget_data']['worklist']['work']['end'], ['class' => 'form-control date-mask', 'tabindex' => 5])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Alasan Berhenti</label>
				</div>	
				<div class="col-md-10">
					{!!Form::textarea('reason_end_job', $WorkComposer['widget_data']['worklist']['work']['reason_end_job'], ['class' => 'form-control', 'tabindex' => 6])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $WorkComposer['widget_data']['worklist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif