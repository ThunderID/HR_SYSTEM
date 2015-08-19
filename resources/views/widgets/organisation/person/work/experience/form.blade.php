@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Pekerjaan ' : 'Ubah Pekerjaan '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $WorkComposer['widget_data']['worklist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">
				<label class="control-label">Perusahaan</label>
				{!!Form::input('text', 'organisation', $WorkComposer['widget_data']['worklist']['work']['organisation'], ['class' => 'form-control'])!!}
			</div>
			<div class="form-group">
				<label class="control-label">Posisi</label>
				{!!Form::input('text', 'position', $WorkComposer['widget_data']['worklist']['work']['position'], ['class' => 'form-control'])!!}
			</div>
			<div class="form-group">
				<label class="control-label">Status</label>
				{!!Form::select('status', ['contract' => 'Kontrak', 'internship' => 'Magang', 'trial' => 'Probation', 'permanent' => 'Tetap', 'others' => 'Lainnya'], $WorkComposer['widget_data']['worklist']['work']['status'], ['class' => 'form-control select2']) !!}
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Start</label>
						@if (isset($WorkComposer['widget_data']['worklist']['work']['start'])&&($WorkComposer['widget_data']['worklist']['work']['start']!=null))
							<?php $date_start = date('d-m-Y', strtotime($WorkComposer['widget_data']['worklist']['work']['start'])); ?>
						@else
							<?php $date_start = null; ?>
						@endif
						{!!Form::input('text', 'start', $date_start, ['class' => 'form-control date-mask'])!!}
					</div>
				</div>	
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">End</label>
						@if (isset($WorkComposer['widget_data']['worklist']['work']['end'])&&($WorkComposer['widget_data']['worklist']['work']['end']!=null))
							<?php $date_end = date('d-m-Y', strtotime($WorkComposer['widget_data']['worklist']['work']['end'])); ?>
						@else
							<?php $date_end = null; ?>
						@endif
						{!!Form::input('text', 'end', $date_end, ['class' => 'form-control date-mask'])!!}
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Alasan Berhenti</label>
				{!!Form::textarea('reason_end_job', $WorkComposer['widget_data']['worklist']['work']['reason_end_job'], ['class' => 'form-control'])!!}
			</div>
			<div class="form-group  text-right">
				<a href="{{ $WorkComposer['widget_data']['worklist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif