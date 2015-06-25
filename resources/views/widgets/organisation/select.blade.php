@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $OrganisationComposer['widget_data']['organisationlist']['form_url'], 'class' => 'form', 'method' => 'get']) !!}	
		<div class="form-group">			
			<label class="control-label">Akses Perusahaan</label>			
			<select name="org_id" id="org" class="select2 form-control" style="width:100%" tabindex="1">
				@if(isset($OrganisationComposer['widget_data']['organisationlist']['organisation']))
					@foreach($OrganisationComposer['widget_data']['organisationlist']['organisation'] as $key => $value)
						<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
					@endforeach
				@endif
			</select>			
		</div>
		<div class="form-group text-right">			
			<input type="submit" class="btn btn-primary" value="Pilih" tabindex="2">
		</div>
	{!! Form::close() !!}
@overwrite	