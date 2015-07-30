@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Data Kerabat ' : 'Ubah Data Kerabat '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		{!! Form::open(['url' => $RelativeComposer['widget_data']['relativelist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Hubungan</label>
				</div>	
				<div class="col-md-10">
					<select name="relationship" class="form-control">
						<option value="parent" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='parent') selected @endif>Orang Tua</option>
						<option value="spouse" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='spouse') selected @endif>Pasangan (Menikah)</option>
						<option value="partner" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='partner') selected @endif>Pasangan (Tidak Menikah)</option>
						<option value="child" @if(strtolower($RelativeComposer['widget_data']['relativelist']['relative']['relationship'])=='child') selected @endif>Anak</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Kerabat</label>
				</div>	
				<div class="col-md-10">
					@include('widgets.organisation.person.select', [
						'widget_options'		=> 	[
														'personlist'			=>
														[
															'organisation_id'	=> $data['id'],
															'search'			=> ['notid' => $person['id']],
															'sort'				=> ['name' => 'asc'],
															'page'				=> 1,
															'per_page'			=> 100,
															'relative_id'		=> $RelativeComposer['widget_data']['relativelist']['relative']['relative_id'],
														]
													]
					])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $RelativeComposer['widget_data']['relativelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
					<input type="submit" class="btn btn-primary" value="Simpan">
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
