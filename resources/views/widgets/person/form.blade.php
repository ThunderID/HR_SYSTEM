@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $widget_data['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">ID</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'uniqid', $widget_data['data']['uniqid'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Gelar Depan</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'prefix_title', $widget_data['data']['prefix_title'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $widget_data['data']['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Gelar Akhir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'suffix_title', $widget_data['data']['suffix_title'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Tempat Lahir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'place_of_birth', $widget_data['data']['place_of_birth'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Tanggal Lahir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'date_of_birth', $widget_data['data']['date_of_birth'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Jenis Kelamin</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'gender', $widget_data['data']['gender'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	