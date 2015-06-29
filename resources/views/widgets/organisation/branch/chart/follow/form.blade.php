@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Kalender Kerja ' : 'Ubah Kalender Kerja '). '"'.$chart['name'].'"'}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $FollowComposer['widget_data']['followlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Kalender</label>
				@include('widgets.organisation.calendar.select', [
					'widget_options'		=> 	[
													'calendarlist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> ['name' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'calendar_id'		=> $FollowComposer['widget_data']['followlist']['follow']['calendar_id'],
														'tabindex'			=> '1'
													]
												]
				])				
			</div>
			<div class="form-group text-right">				
				<a href="{{ $FollowComposer['widget_data']['followlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="3">Batal</a>
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