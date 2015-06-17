@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Karir </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $WorkComposer['widget_data']['worklist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Perusahaan</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'organisation', $WorkComposer['widget_data']['worklist']['work']['organisation'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Posisi</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'position', $WorkComposer['widget_data']['worklist']['work']['position'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Status</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'status', $WorkComposer['widget_data']['worklist']['work']['status'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Start</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'start', $WorkComposer['widget_data']['worklist']['work']['start'], ['class' => 'form-control'])!!}
			</div>
			<div class="col-md-1 col-md-offset-1">
				<label class="control-label">End</label>
			</div>	
			<div class="col-md-4">
				{!!Form::input('text', 'end', $WorkComposer['widget_data']['worklist']['work']['end'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Alasan Berhenti</label>
			</div>	
			<div class="col-md-10">
				{!!Form::textarea('reason_end_job', $WorkComposer['widget_data']['worklist']['work']['reason_end_job'], ['class' => 'form-control'])!!}
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