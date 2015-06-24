@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Template Dokumen' : 'Ubah Template Dokumen '. $TemplateComposer['widget_data']['templatelist']['template']['field']}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $TemplateComposer['widget_data']['templatelist']['form_url'], 'class' => 'form-horizontal no_enter']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Input</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'field', $TemplateComposer['widget_data']['templatelist']['template']['field'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Tipe</label>
				</div>	
				<div class="col-md-10">
					<select id="Type" class="form-control form-control input-md" name="type">
						<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='numeric') selected @endif value="numeric">Angka</option>
						<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='date') selected @endif value="date">Tanggal</option>
						<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='string') selected @endif value="string">Teks Singkat</option>
						<option @if($TemplateComposer['widget_data']['templatelist']['template']['type']=='text') selected @endif value="text">Teks Panjang</option>
					</select>
				</div>
			</div>

			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $TemplateComposer['widget_data']['templatelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
				</div>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif