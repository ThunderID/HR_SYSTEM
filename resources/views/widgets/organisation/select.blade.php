@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@section('widget_title')	
	
@overwrite

@section('widget_body')
	{!! Form::open(['url' => $OrganisationComposer['widget_data']['organisationlist']['form_url'], 'class' => 'form-horizontal', 'method' => 'get']) !!}	
		<div class="form-group">
			<div class="col-md-3">
				<label class="control-label">Akses Perusahaan</label>
			</div>	
			<div class="col-md-9">
				<select name="org_id" id="org" class="select2 form-control" style="width:100%">
					@if(isset($OrganisationComposer['widget_data']['organisationlist']['organisation']))
						@foreach($OrganisationComposer['widget_data']['organisationlist']['organisation'] as $key => $value)
							<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-12 text-right">
				<input type="submit" class="btn btn-primary" value="Pilih">
			</div>
		</div>
	{!! Form::close() !!}
@overwrite	