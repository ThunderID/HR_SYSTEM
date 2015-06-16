@extends('widget_templates.'.($widget_template ? $widget_template : 'plain'))

@if ((isset($widget_errors) && !$widget_errors->count() || !isset($widget_errors)))
	@section('widget_title')	
		{{ $widget_title or 'Cabang' }}
	@overwrite

	@section('widget_body')
		<div class="clearfix">&nbsp;</div>
		{!! Form::open(['url' => $BranchComposer['widget_data']['branchlist']['form_url'], 'class' => 'form-horizontal']) !!}	
			<div class="form-group">
				<div class="col-md-2">
					<label class="control-label">Nama</label>
				</div>	
				<div class="col-md-10">
					{!!Form::input('text', 'name', $BranchComposer['widget_data']['branchlist']['branch']['name'], ['class' => 'form-control', 'placeholder' => 'Nama'])!!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-12 text-right">
					<a href="{{ $BranchComposer['widget_data']['branchlist']['route_edit'] }}" class="btn btn-default mr-5">Batal</a>
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