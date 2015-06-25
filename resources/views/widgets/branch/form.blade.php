@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')	
		<h1> {{ is_null($id) ? 'Tambah Cabang' : 'Ubah '. $BranchComposer['widget_data']['branchlist']['branch']['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $BranchComposer['widget_data']['branchlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $BranchComposer['widget_data']['branchlist']['branch']['name'], ['class' => 'form-control', 'placeholder' => 'Nama', 'tabindex' => '1'])!!}				
			</div>
			<div class="form-group text-right">				
				<a href="{{ $BranchComposer['widget_data']['branchlist']['route_edit'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="2">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif