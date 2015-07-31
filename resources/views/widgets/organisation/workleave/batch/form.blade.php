@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1> {{ is_null($id) ? 'Batch ' : 'Ubah '}} "{{$workleave['name']}}"</h1> 
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $widget_options['workleavelist']['form_url'], 'class' => 'form no_enter']) !!}	

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Tahun</label>
					{!!Form::input('text', 'start', '', ['class' => 'form-control year-mask', 'tabindex' => 1])!!}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Tanggal Mulai Cuti Bersama</label>
					{!!Form::input('text', 'joint_start', '', ['class' => 'form-control date-mask', 'tabindex' => 2])!!}
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Tanggal Akhir Cuti Bersama</label>
					{!!Form::input('text', 'joint_end', '', ['class' => 'form-control date-mask', 'tabindex' => 3])!!}
				</div>
			</div>
		</div>

		<div class="form-group text-right">
			<a href="{{ $widget_options['workleavelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
			<input type="submit" class="btn btn-primary" value="Simpan">
		</div>
	{!! Form::close() !!}
@overwrite	
