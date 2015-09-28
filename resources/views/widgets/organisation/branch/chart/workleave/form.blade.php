@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Cuti Kerja ' : 'Ubah Cuti Kerja '). '"'.$chart['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $ChartWorkleaveComposer['widget_data']['workleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Cuti</label>
				@include('widgets.organisation.workleave.select', [
					'widget_options'		=> 	[
													'workleavelist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> ['name' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'workleave_id'		=> $ChartWorkleaveComposer['widget_data']['workleavelist']['workleave']['workleave_id'],
														'tabindex'			=> '1'
													]
												]
				])				
			</div>
			<div class="form-group">
				<label class="control-label">Rules</label>
				{!! Form::textarea('rules', $ChartWorkleaveComposer['widget_data']['workleavelist']['workleave']['rules'], ['class' => 'form-control', 'rows' => '2']) !!}
			</div>
			<div class="form-group text-right">				
				<a href="{{ $ChartWorkleaveComposer['widget_data']['workleavelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
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