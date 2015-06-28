@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Jatah Cuti ' : 'Ubah Jatah Cuti '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">
				<label class="control-label">Cuti</label>
				@include('widgets.organisation.workleave.select', [
					'widget_options'		=> 	[
													'workleavelist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> [],
														'sort'				=> ['name' => 'asc'],
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'workleave_id'		=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['workleave_id'],
														'tabindex'			=> 1,
													]
												]
				])
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Start</label>
						{!!Form::input('text', 'start', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start'], ['class' => 'form-control date-mask', 'tabindex' => 2])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">End</label>
						{!!Form::input('text', 'end', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end'], ['class' => 'form-control date-mask', 'tabindex' => 3])!!}
					</div>	
				</div>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label for="">
					{!!Form::checkbox('is_default', '1', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['is_default'], ['class' => '', 'tabindex' => 4])!!} Hak Cuti
					</label>
				</div>
			</div>
			<div class="form-group text-right">
				<a href="{{ $PersonWorkleaveComposer['widget_data']['personworkleavelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif