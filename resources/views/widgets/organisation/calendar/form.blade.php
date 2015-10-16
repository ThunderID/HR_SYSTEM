@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
	<h1> {{ is_null($id) ? 'Tambah Kalender' : 'Ubah Kalender '. $CalendarComposer['widget_data']['calendarlist']['calendar']['name']}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
			{!! Form::open(['url' => $CalendarComposer['widget_data']['calendarlist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">				
				<label class="control-label">Mengikuti Kalender</label>				
					@include('widgets.organisation.calendar.select', [
					'widget_options'		=> 	[
													'calendarlist'			=>
													[
														'organisation_id'	=> Session::get('user.organisationids'),
														'search'			=> ['notid' => (is_null($id) ? 0 : $id)],
														'sort'				=> ['name' => 'asc'],
														'page'				=> 1,
														'per_page'			=> 100,
														'calendar_id'		=> (isset($CalendarComposer['widget_data']['calendarlist']['calendar']['impot_from_id']) ? $CalendarComposer['widget_data']['calendarlist']['calendar']['impot_from_id'] : 0),
														'tabindex'			=> 1,
													]
												]
				])
			</div>
			<div class="form-group">				
				<label class="control-label">Nama</label>				
				{!!Form::input('text', 'name', $CalendarComposer['widget_data']['calendarlist']['calendar']['name'], ['class' => 'form-control', 'tabindex' => '2'])!!}				
			</div>
			<div class="form-group">				
				<label class="control-label">Workdays</label>				
				{!!Form::input('text', 'workdays', $CalendarComposer['widget_data']['calendarlist']['calendar']['workdays'], ['class' => 'form-control select2-tag-days', 'tabindex' => '3'])!!}				
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Start</label>
						{!!Form::input('text', 'start', $CalendarComposer['widget_data']['calendarlist']['calendar']['start'], ['class' => 'form-control time-mask', 'tabindex' => '4'])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">End</label>
						{!!Form::input('text', 'end', $CalendarComposer['widget_data']['calendarlist']['calendar']['end'], ['class' => 'form-control time-mask', 'tabindex' => '5'])!!}
					</div>
				</div>
			</div>
			<div class="row mt-10">
				<div class="col-sm-12">
					<h4>Break Idle</h4>
				</div>
			</div>
			<div class="row tmp_break_idle">
			</div>
			<div class="form-group text-right">				
				<a href="{{ $CalendarComposer['widget_data']['calendarlist']['route_back'] }}" class="btn btn-default mr-5" tabindex="14">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="13">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif