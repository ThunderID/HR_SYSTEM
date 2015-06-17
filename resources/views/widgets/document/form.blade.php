@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
<h1> Template Dokumen </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'name', $DocumentComposer['widget_data']['documentlist']['document']['name'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Kategori</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'tag', $DocumentComposer['widget_data']['documentlist']['document']['tag'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Template</label>
			</div>	
			<div class="col-md-10">
				{!!Form::textarea('template', $DocumentComposer['widget_data']['documentlist']['document']['template'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $DocumentComposer['widget_data']['documentlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	