@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	<h1> @if(is_null($id)) Tambah Organisasi @else Ubah "{{$OrganisationComposer['widget_data']['organisationlist']['organisation']['name']}}" @endif  </h1>
@overwrite

@section('widget_body')
	<div class="clearfix">&nbsp;</div>
	{!! Form::open(['url' => $OrganisationComposer['widget_data']['organisationlist']['form_url'], 'class' => 'form-horizontal']) !!}	
		<div class="form-group">
			<div class="col-md-2">
				<label class="control-label">Nama</label>
			</div>	
			<div class="col-md-10">
				{!! Form::input('text', 'name', $OrganisationComposer['widget_data']['organisationlist']['organisation']['name'], ['class' => 'form-control']) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<a href="{{ $OrganisationComposer['widget_data']['organisationlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	