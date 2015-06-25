@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	<h1> @if(is_null($id)) Tambah Organisasi @else Ubah "{{$OrganisationComposer['widget_data']['organisationlist']['organisation']['name']}}" @endif  </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $OrganisationComposer['widget_data']['organisationlist']['form_url'], 'class' => 'form no_enter']) !!}	
		<div class="form-group">
			<label class="control-label">Nama</label>
			{!! Form::input('text', 'name', $OrganisationComposer['widget_data']['organisationlist']['organisation']['name'], ['class' => 'form-control', 'tabindex' => '1']) !!}
		</div>
		<div class="form-group text-right">			
			<a href="{{ $OrganisationComposer['widget_data']['organisationlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
			<input type="submit" class="btn btn-primary" value="Simpan" tabindex="2">
		</div>
	{!! Form::close() !!}
@overwrite	