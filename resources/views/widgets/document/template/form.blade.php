@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Dokumen' : 'Ubah Template Dokumen '. $TemplateComposer['widget_data']['templatelist']['template']['field']}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $TemplateComposer['widget_data']['templatelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Input</label>
						{!!Form::input('text', 'field', $TemplateComposer['widget_data']['templatelist']['template']['field'], ['class' => 'form-control', 'tabindex' => '1'])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Tipe</label>
						<select id="Type" class="form-control form-control input-md" name="type" tabindex="2">
							<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='numeric') selected @endif value="numeric">Angka</option>
							<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='date') selected @endif value="date">Tanggal</option>
							<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='string') selected @endif value="string">Teks Singkat</option>
							<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='text') selected @endif value="text">Teks Panjang</option>
						</select>
					</div>
				</div>
			</div>				
			<div class="form-group text-right">				
				<a href="{{ $TemplateComposer['widget_data']['templatelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="4">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="3">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif