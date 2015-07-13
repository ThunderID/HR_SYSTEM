@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if (!$widget_error_count)
	@section('widget_title') 
		<h1> {{ is_null($id) ? 'Tambah Otentikasi ' : 'Ubah Otentikasi '}} </h1> 
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
			{!! Form::open(['url' => $AuthenticationComposer['widget_data']['authentication']['form_url'], 'class' => 'form no_enter']) !!}	
				<div class="form-group">
					<label class="control-label">Organisation</label>
					{!! Form::input('text', 'org_id', '', ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					<label class="control-label">Pekerjaan</label>
					{!! Form::input('text', 'work_id', '', ['class' => 'form-control']) !!}
				</div>
				<div class="form-group">
					<label class="control-label">Level Otentikasi</label>
					{!! Form::select('level', [
						'1' => 'Level 1',
						'2' => 'Level 2',
						'3' => 'Level 3',
						'4' => 'Level 4',
						'5' => 'Level 5'
					], null, ['class' => 'form-control select2']) !!}					
				</div>
				<div class="form-group">
					<div class="col-md-12 text-right">
						<a href="{{ $AuthenticationComposer['widget_data']['authentication']['route_back'] }}" class="btn btn-default mr-5">Batal</a>
						<input type="submit" class="btn btn-primary" value="Simpan">
					</div>
				</div>
			{!! Form::close() !!}
		</div>
	@overwrite
@else
	@section('widget_title')
	@overwrite

	@section('widget_body')
	@overwrite
@endif