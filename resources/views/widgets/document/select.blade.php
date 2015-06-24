@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Dokumen Karyawan' : 'Ubah Dokumen Karyawan '. $DocumentComposer['widget_data']['documentlist']['document']['name']}} </h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form-horizontal', 'method' => 'get']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Jenis Dokumen</label>
				</div>	
				<div class="col-md-10">
					<select name="doc_id" id="doc" class="form-control">
						@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
							@foreach($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
								<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
							@endforeach
						@endif
					</select>
				</div>
			</div>
			<input type="hidden" class="form-control" id="org_id" name="org_id" value="{{$data['id']}}">
			<input type="hidden" class="form-control" id="person_id" name="person_id" value="{{$person['id']}}">
			<div class="form-group">
				<div class="col-md-12 text-right">
					<input type="submit" class="btn btn-primary" value="Pilih">
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