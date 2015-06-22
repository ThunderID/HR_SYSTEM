@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)

	@section('widget_title')
	<h1> {{ (is_null($id) ? 'Tambah Kalender Kerja Posisi ' : 'Ubah Kalender Kerja Posisi '). $branch['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $FollowComposer['widget_data']['followlist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Kalender</label>
				</div>	
				<div class="col-md-10">
					@include('widgets.calendar.select', [
						'widget_options'		=> 	[
														'calendarlist'			=>
														[
															'organisation_id'	=> $data['id'],
															'search'			=> [],
															'sort'				=> ['name' => 'asc'],
															'page'				=> 1,
															'per_page'			=> 100,
															'calendar_id'		=> $FollowComposer['widget_data']['followlist']['follow']['calendar_id'],
														]
													]
					])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $FollowComposer['widget_data']['followlist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
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