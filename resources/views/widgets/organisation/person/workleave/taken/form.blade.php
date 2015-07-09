@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))
@if (!$widget_error_count)
	@section('widget_title')
		<h1> {{ is_null($id) ? 'Tambah Pengambilan Cuti ' : 'Ubah Pengambilan Cuti '}} "{{$person['name']}}" </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $PersonWorkleaveComposer['widget_data']['personworkleavelist']['form_url'], 'class' => 'form no_enter']) !!}	
			<div class="form-group">
				<label class="control-label">Diambil Dari</label>
				@include('widgets.organisation.person.workleave.select', [
					'widget_options'		=> 	[
													'workleavelist'			=>
													[
														'organisation_id'	=> $data['id'],
														'search'			=> ['personid' => $person['id'],'status' => 'CN', 'ondate' => [date('Y-m-d', strtotime('first day of January this year')), date('Y-m-d', strtotime('last day of December this year'))]],
														'sort'				=> ['name' => 'asc'],
														'page'				=> (Input::has('page') ? Input::get('page') : 1),
														'per_page'			=> 100,
														'workleave_id'		=> $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['person_workleave_id'],
														'tabindex'			=> 1,
													]
												]
				])
			</div>

			{!!Form::hidden('type', 'taken')!!}
			{!!Form::hidden('status', 'confirmed')!!}

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Keterangan (Singkat)</label>
						{!!Form::input('text', 'name', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['name'], ['class' => 'form-control', 'tabindex' => 2])!!}
					</div>
					<?php /*
					<div class="form-group">
						<label class="control-label">Jenis</label>
						<select name="status" class="form-control">
							<option value="confirmed" @if($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['status']=='confirmed') selected @endif>Konfirmasi Cuti</option>
						</select>
					</div>
					*/?>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Quota</label>
						{!!Form::input('number', 'quota', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['quota'], ['class' => 'form-control', 'tabindex' => 3])!!}
					</div>	
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">Start</label>
						{!!Form::input('text', 'start', isset($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start']) ? date('d-m-Y', strtotime($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['start'])) : '', ['class' => 'form-control date-mask', 'tabindex' => 4])!!}
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label">End</label>
						{!!Form::input('text', 'end', isset($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end']) ? date('d-m-Y', strtotime($PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['end'])) : '', ['class' => 'form-control date-mask', 'tabindex' => 5])!!}
					</div>	
				</div>
			</div>
			<div class="form-group">
				<label class="control-label">Notes</label>
				{!!Form::textarea('notes', $PersonWorkleaveComposer['widget_data']['personworkleavelist']['workleave']['notes'], ['class' => 'form-control', 'tabindex' => 6])!!}
			</div>
			<div class="form-group text-right">
				<a href="{{ $PersonWorkleaveComposer['widget_data']['personworkleavelist']['route_back'] }}" class="btn btn-default mr-5" tabindex="7">Batal</a>
				<input type="submit" class="btn btn-primary" value="Simpan" tabindex="8">
			</div>
		{!! Form::close() !!}
	@overwrite	
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif