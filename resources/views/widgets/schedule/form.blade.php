@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Jadwal </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $ScheduleComposer['widget_data']['schedulelist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Label</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $ScheduleComposer['widget_data']['schedulelist']['schedule']['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Status</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'status', $ScheduleComposer['widget_data']['schedulelist']['schedule']['status'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Tanggal</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'on', $ScheduleComposer['widget_data']['schedulelist']['schedule']['on'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Start</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'start', $ScheduleComposer['widget_data']['schedulelist']['schedule']['start'], ['class' => 'form-control'])!!}
			</div>
			<div class="col-md-1 col-md-offset-1">
				<label class="control-label">End</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'end', $ScheduleComposer['widget_data']['schedulelist']['schedule']['end'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $ScheduleComposer['widget_data']['schedulelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	