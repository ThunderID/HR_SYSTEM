@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Data Karyawan </h1>
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $PersonComposer['widget_data']['personlist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="clearfix">&nbsp;</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">ID</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'uniqid', $PersonComposer['widget_data']['personlist']['person']['uniqid'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Gelar Depan</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'prefix_title', $PersonComposer['widget_data']['personlist']['person']['prefix_title'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $PersonComposer['widget_data']['personlist']['person']['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Gelar Akhir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'suffix_title', $PersonComposer['widget_data']['personlist']['person']['suffix_title'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Tempat Lahir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'place_of_birth', $PersonComposer['widget_data']['personlist']['person']['place_of_birth'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Tanggal Lahir</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'date_of_birth', $PersonComposer['widget_data']['personlist']['person']['date_of_birth'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Jenis Kelamin</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'gender', $PersonComposer['widget_data']['personlist']['person']['gender'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $PersonComposer['widget_data']['personlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	