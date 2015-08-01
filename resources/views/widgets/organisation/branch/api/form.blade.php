@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah API Cabang ' : 'Ubah API Cabang '). '"'.$branch['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ApiComposer['widget_data']['apilist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">API KEY</label>
						{!!Form::input('text', 'client', $ApiComposer['widget_data']['apilist']['api']['client'], ['class' => 'form-control', 'tabindex' => '1'])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						<label class="control-label">API SECRET</label>
						{!!Form::input('text', 'secret', $ApiComposer['widget_data']['apilist']['api']['secret'], ['class' => 'form-control', 'tabindex' => '2'])!!}
					</div>
				</div>
			</div>	
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">APP ID</label>
						{!!Form::input('text', 'workstation_address', $ApiComposer['widget_data']['apilist']['api']['workstation_address'], ['class' => 'form-control', 'tabindex' => '3'])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">				
						<label class="control-label">PC Name</label>
						{!!Form::input('text', 'workstation_name', $ApiComposer['widget_data']['apilist']['api']['workstation_name'], ['class' => 'form-control', 'tabindex' => '4'])!!}
					</div>
				</div>
			</div>	
			<div class="form-group">				
				<div class="checkbox">
					<label>
						{!!Form::checkbox('is_active', '1', ($ApiComposer['widget_data']['apilist']['api']['is_active'] ? $ApiComposer['widget_data']['apilist']['api']['is_active'] : 'checked'), ['class' => '', 'tabindex' => '5'])!!} Aktif
					</label>
				</div>				
			</div>
			<div class="form-group text-right">				
				<a href="{{ $ApiComposer['widget_data']['apilist']['route_back'] }}" class="btn btn-default mr-5" tabindex="4">Batal</a>
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