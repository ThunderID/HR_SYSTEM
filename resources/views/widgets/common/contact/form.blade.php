@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Kontak ' : 'Ubah Kontak '. (isset($ContactComposer['widget_data']['contactlist']['contact']['branch']) ? ' Cabang "'.$ContactComposer['widget_data']['contactlist']['contact']['branch']['name'].'"' : '"'.$ContactComposer['widget_data']['contactlist']['contact']['person']['name'].'"')}} </h1> 
	@overwrite

	@section('widget_body')		
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ContactComposer['widget_data']['contactlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Item</label>				
				{!!Form::input('text', 'item', $ContactComposer['widget_data']['contactlist']['contact']['item'], ['class' => 'form-control select2-tag-contact', 'style' => 'width:100%', 'tabindex' => '1'])!!}				
			</div>
			<div class="form-group">				
				<label class="control-label">Kontak</label>				
				{!!Form::input('text', 'value', $ContactComposer['widget_data']['contactlist']['contact']['value'], ['class' => 'form-control val-contact', 'tabindex' => '2'])!!}				
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						{!!Form::checkbox('is_default', '1', $ContactComposer['widget_data']['contactlist']['contact']['is_default'], ['class' => '', 'tabindex' => '3'])!!} Aktif
					</label>
				</div>				
			</div>
			<div class="form-group text-right">				
				<a href="{{ $ContactComposer['widget_data']['contactlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="4">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif