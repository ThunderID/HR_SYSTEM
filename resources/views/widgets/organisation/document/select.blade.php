@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Dokumen ' : 'Ubah Dokumen  '. $DocumentComposer['widget_data']['documentlist']['document']['name']}} "{{$person['name']}}"</h1> 
	@overwrite

	@section('widget_body')	  
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $DocumentComposer['widget_data']['documentlist']['form_url'], 'class' => 'form', 'method' => 'get']) !!}	
			<div class="form-group">				
				<label class="control-label">Jenis Dokumen</label>				
				<select name="doc_id" id="doc" class="form-control select_doc_person" tabindex="1">
					@if(isset($DocumentComposer['widget_data']['documentlist']['document']))
						@foreach($DocumentComposer['widget_data']['documentlist']['document'] as $key => $value)
							@if ($key==0)
								<?php $keyid= $value['id']; $keyname= $value['name']; ?>
							@endif
							<option value="{{ $value['id'] }}" data-name="{{ $value['name'] }}">{{ $value['name'] }}</option>
						@endforeach
					@endif
				</select>				
			</div>
			<div id="doc_template"></div>
			<input type="hidden" class="form-control" id="org_id" name="org_id" value="{{$data['id']}}">
			<input type="hidden" class="form-control" id="person_id" name="person_id" value="{{$person['id']}}">
			{{-- <div class="form-group">
				<a href="javascript:;" class="btn btn-default btn_add_doclist">Tambah Jenis Dokumen</a>				
			</div> --}}
			<div class="form-group text-right">
				<input type="submit" class="btn btn-primary" value="Input Form" tabindex="2">
				<a href="javascript:;" class="btn btn-primary import_doc" data-toggle="modal" data-target="#import_csv_person" data-action="{{ route('hr.person.documents.store') }}" tabindex="3" data-org_id="{{ $data['id'] }}" data-doc_id="{{ $keyid }}" data-doc_name="{{ $keyname }}">Import CSV</a>
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif