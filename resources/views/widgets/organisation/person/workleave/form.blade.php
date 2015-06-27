@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Jatah Cuti ' : 'Ubah Jatah Cuti '}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Cuti</label>
				</div>	
				<div class="col-md-10">
						@include('widgets.workleave.select', [
							'widget_options'		=> 	[
															'workleavelist'			=>
															[
																'organisation_id'	=> $data['id'],
																'search'			=> [],
																'sort'				=> ['name' => 'asc'],
																'page'				=> (Input::has('page') ? Input::get('page') : 1),
																'per_page'			=> 100,
																'workleave_id'		=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['workleave_id'],
															]
														]
						])
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Start</label>
				</div>	
				<div class="col-md-4">
					{!!Form::input('text', 'start', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start'], ['class' => 'form-control'])!!}
				</div>
				<div class="col-md-1 col-md-offset-1">
					<label class="control-label">End</label>
				</div>	
				<div class="col-md-4">
					{!!Form::input('text', 'end', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end'], ['class' => 'form-control'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Hak Cuti</label>
				</div>	
				<div class="col-md-10">
					<div class="checkbox">
						<label for="">
						{!!Form::checkbox('is_default', '1', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['is_default'], ['class' => ''])!!}
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $PersonWorkleaveComposer['widget_data']['personworkleavelist']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
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