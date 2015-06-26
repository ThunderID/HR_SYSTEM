@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Dokumen Karyawan' : 'Ubah Dokumen Karyawan '. $DocumentComposer['widget_data']['documentlist']['document']['name']}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form', 'method' => 'get']) !!}	
			<div class="form-group">				
				<label class="control-label">Jenis Dokumen</label>				
				<select name="doc_id" id="doc" class="form-control select2" tabindex="1">
					@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
						@foreach($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
							<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
						@endforeach
					@endif
				</select>				
			</div>
			<input type="hidden" class="form-control" id="org_id" name="org_id" value="{{$data['id']}}">
			<input type="hidden" class="form-control" id="person_id" name="person_id" value="{{$person['id']}}">
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Pilih" tabindex="2">				
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif