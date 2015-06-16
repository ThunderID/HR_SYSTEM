@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Jabatan </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $widget_data['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $widget_data['chart-'.$widget_data['identifier']]['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Bawahan Dari</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'path', $widget_data['chart-'.$widget_data['identifier']]['path'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Departemen</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'tag', $widget_data['chart-'.$widget_data['identifier']]['tag'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="text-left">Grade</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'grade', $widget_data['chart-'.$widget_data['identifier']]['grade'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="text-left">Jumlah Pegawai Minimum</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'min_employee', $widget_data['chart-'.$widget_data['identifier']]['min_employee'], ['class' => 'form-control'])!!}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-2">
				<label class="text-left">Jumlah Pegawai Maximum</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'max_employee', $widget_data['chart-'.$widget_data['identifier']]['max_employee'], ['class' => 'form-control'])!!}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-2">
				<label class="text-left">Jumlah Pegawai Ideal</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'ideal_employee', $widget_data['chart-'.$widget_data['identifier']]['ideal_employee'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	