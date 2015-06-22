@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Kontak ' : 'Ubah Kontak '. (isset($ContactComposer['widget_data']['contactlist']['contact']['branch']) ? ' Cabang '.$ContactComposer['widget_data']['contactlist']['contact']['branch']['name'] : $ContactComposer['widget_data']['contactlist']['contact']['person']['name'])}} </h1> 
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $ContactComposer['widget_data']['contactlist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Item</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'item', $ContactComposer['widget_data']['contactlist']['contact']['item'], ['class' => 'select2-tag-contact', 'style' => 'width:100%'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Kontak</label>
			</div>	
			<div class="col-md-10">
				{!!Form::input('text', 'value', $ContactComposer['widget_data']['contactlist']['contact']['value'], ['class' => 'form-control'])!!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Aktif</label>
			</div>	
			<div class="col-md-10">
				<div class="checkbox">
					<label for="">
					{!!Form::checkbox('is_default', '1', $ContactComposer['widget_data']['contactlist']['contact']['is_default'], ['class' => ''])!!}
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $ContactComposer['widget_data']['contactlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	