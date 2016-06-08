@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah IP Whitelist ' : 'Ubah IP Whitelist ') }} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $IPWhitelistComposer['widget_data']['ipwhitelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">IP</label>
						{!! Form::input('text', 'ip', $IPWhitelistComposer['widget_data']['ipwhitelist']['ipwhitelist']['ip'], ['class' => 'form-control', 'tabindex' => '1']) !!}
					</div>
				</div>
			</div>
			<div class="form-group text-right">				
				<a href="{{ $IPWhitelistComposer['widget_data']['ipwhitelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
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